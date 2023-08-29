<?php
declare(strict_types=1);

namespace Edde\File;

use Edde\Database\Exception\DuplicateEntryException;
use Edde\Dto\DtoServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\File\Exception\FileNotFoundException;
use Edde\File\Exception\FileNotReadableException;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\File\Schema\DB\FileUpdateRequestSchema;
use Edde\File\Schema\DB\FileUpsertSchema;
use Edde\File\Schema\Query\FileQuerySchema;
use Edde\Log\LoggerTrait;
use Edde\Math\RandomServiceTrait;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Stream\FileStream;
use Edde\Stream\IStream;
use Edde\User\CurrentUserServiceTrait;
use Edde\Uuid\UuidServiceTrait;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use function array_merge;
use function dirname;
use function file_exists;
use function is_readable;
use function ltrim;
use function microtime;
use function rawurlencode;
use function rtrim;
use function sprintf;
use function str_replace;
use function touch;
use function unlink;

class FileService implements IFileService {
	use FileRepositoryTrait;
	use LoggerTrait;
	use CurrentUserServiceTrait;
	use MimeServiceTrait;
	use SmartServiceTrait;
	use DtoServiceTrait;
	use UuidServiceTrait;
	use RandomServiceTrait;

	/**
	 * Name of configuration (or container entry) item to get the base path for the
	 * filesystem.
	 */
	const CONFIG_ROOT = 'file-service.root';

	/** @var Directory */
	protected $directory;

	public function __construct(string $root) {
		$this->directory = new Directory($root);
	}

	public function root(): Directory {
		return $this->directory;
	}

	/**
	 * @inheritdoc
	 */
	public function accept(string $file, string $path, string $name, float $ttl = null): SmartDto {
		return FileStream::openRead($_FILES[$file]['tmp_name'])
			->use(function (IStream $stream) use ($path, $name, $ttl) {
				return $this->store($stream, $path, $name, $ttl, $this->currentUserService->optionalId());
			});
	}

	/**
	 * @inheritdoc
	 */
	public function chunk(IStream $stream, string $name, ?string $userId = null): SmartDto {
		$file = $this->file('/chunk', $name, 'application/vnd.chunk', 60 * 5, $userId);

		FileStream::openAppend($file->native)->useToStream($stream);

		return $this->fileMapper->item($this->fileRepository->change([
			'id'   => $file->id,
			'size' => $this->sizeOf($file->native),
		]));
	}

	public function commit(string $chunk, string $path, string $name = null, bool $replace = false): SmartDto {
		$file = $this->fileRepository->findByPath('/chunk', $chunk);

		$source = [
			'id'      => $file->id,
			'path'    => $path,
			'mime'    => $this->mimeService->detect($file->native),
			'ttl'     => null,
			'user_id' => $file->user_id,
		];
		$name && $source['name'] = $name;

		try {
			return $this->fileMapper->item($this->fileRepository->change($source));
		} catch (DuplicateEntryException $exception) {
			$name && $this->fileRepository->deleteWhere()->where('path', $path)->where('name', $name)->execute();
			return $this->fileMapper->item($this->fileRepository->change($source));
		}
	}

	public function sizeOf(string $file): int {
		return $this->directory->sizeOf($file);
	}

	public function gc(bool $force = false): SmartDto {
		$start = microtime(true);
		$gc = [
			'records' => 0,
			'files'   => 0,
		];
		$files = 0;
		if ($gc['hit'] = ($force || $this->randomService->isHit(1 / 1000))) {
			$gc['records'] = $this->fileRepository
				->table()
				->delete()
				->whereNotNull('ttl')
				->where('ttl', '<', microtime(true))
				->execute()
				->rowCount;

			$source = $this
				->directory
				->files('/files')
				->filter(function (StorageAttributes $storageAttributes) {
					$file = $this->fileRepository->findByNative($this->directory->prefix($storageAttributes->path()));
					return $file === null || ($file->ttl !== null && $file->ttl < microtime(true));
				});
			/** @var $item FileAttributes */
			foreach ($source as $item) {
				$this->fileRepository->deleteByNative($file = $this->directory->prefix($item->path()));
				try {
					$this->directory->delete($item->path());
					$this->logger->info(sprintf('Removing dead file by TTL [%s]', $file), ['tags' => ['file']]);
				} catch (Throwable $exception) {
					$this->logger->error($exception, ['tags' => ['file']]);
					/**
					 * swallow - there could be some reasons the file cannot be deleted; it could be taken by another
					 * GC run
					 */
				}
				$files++;
			}
			foreach ($this->fileRepository->all() as $file) {
				try {
					$this->assert($file->native);
				} catch (FileNotFoundException|FileNotReadableException $e) {
					$this->fileRepository->deleteWith(
						$this->smartService->from(
							[
								'filter' => [
									'native' => $file->getValue('native'),
								],
							],
							FileQuerySchema::class
						)
					);
					$this->logger->info(sprintf('Removing dead file by missing (not readable) file [%s]', $file->native), ['tags' => ['file']]);
					$gc['records']++;
				}
			}
		}
		return $this->dtoService->fromArray(GcResultDto::class, array_merge($gc, [
			'runtime' => microtime(true) - $start,
			'files'   => $files,
		]));
	}

	/**
	 * @inheritdoc
	 */
	public function file(string $path, string $name, string $mime, float $ttl = null, ?string $userId = null): SmartDto {
		$native = $this->directory->prefix('files/' . str_replace('-', '/', $uuid = $this->uuidService->uuid4()) . '/' . $uuid);
		$upsert = [
			'path'   => $path,
			'name'   => $name,
			'mime'   => $mime,
			'userId' => $userId,
			'size'   => -1,
			'ttl'    => $ttl,
			'native' => $this->directory->normalize($native),
		];
		$file = $this->fileRepository->upsert(
			$this->smartService->from(
				[
					'create' => $upsert,
					'update' => $upsert,
					'filter' => [
						'name' => $name,
						'path' => $path,
					],
				],
				FileUpsertSchema::class
			)
		);
		$this->directory->create(
			dirname(
				$this->directory->base($file->getValue('native'))
			)
		);
		touch($file->getValue('native'));
		return $file;
	}

	/**
	 * @inheritdoc
	 */
	public function store(IStream $stream, string $path, string $name, float $ttl = null, ?string $userId = null): SmartDto {
		try {
			$file = $this->file(rtrim($path, '/'), ltrim($name, '/'), 'application/octet-stream', $ttl, $userId);
			FileStream::openWrite($file->getValue('native'))->useToStream($stream);
			return $this->refresh($file->getValue('id'));
		} catch (Throwable $exception) {
			$this->logger->error($exception);
			throw $exception;
		}
	}

	public function refresh(string $fileId): SmartDto {
		$file = $this->fileRepository->find($fileId);
		return $this->fileRepository->update(
			$this->smartService->from(
				[
					'update' => [
						'mime' => $this->mimeService->detect($file->getValue('native')),
						'size' => $this->sizeOf($file->getValue('native')),
					],
					'filter' => [
						'id' => $file->getValue('id'),

					],
				],
				FileUpdateRequestSchema::class
			)
		);
	}

	public function useFile(string $fileId, callable $callback) {
		return $callback($this->fileRepository->find($fileId));
	}

	/**
	 * Consumes a file (file is deleted after usage regardless of success). This method is useful to prevent file pollution
	 * on quite dynamic services (like imports and so).
	 *
	 * @param string   $fileId
	 * @param callable $callback
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 * @throws Throwable
	 */
	public function consumeFile(string $fileId, callable $callback) {
		$file = $this->fileRepository->find($fileId);
		try {
			return $callback($file);
		} finally {
			$this->delete($file->native);
			$this->fileRepository->deleteBy(
				$this->smartService->from(
					[
						'id' => $fileId,
					],
					WithIdentitySchema::class
				)
			);
		}
	}

	public function delete(string $file) {
		@unlink($file);
	}

	/**
	 * Return proper path for the file; also ensures the file exists and is readable.
	 *
	 * @param string $file
	 *
	 * @return string
	 *
	 * @throws FileNotFoundException
	 * @throws FileNotReadableException
	 */
	public function assert(string $file): string {
		if (!file_exists($file)) {
			throw new FileNotFoundException(sprintf('Requested file not found [%s].', $file));
		}
		if (!is_readable($file)) {
			throw new FileNotReadableException(sprintf('Requested file is not readable [%s].', $file));
		}
		return $file;
	}

	public function send(string $fileId, ResponseInterface $response, ?string $name = null): ResponseInterface {
		return $this->useFile($fileId, function (SmartDto $file) use ($response, $name) {
			return $response
				->withHeader('Content-Type', $file->getValue('mime'))
				->withHeader('Cache-Control', 'private, max-age=0, must-revalidate')
				->withHeader('Content-Disposition', 'inline; filename=' . rawurlencode($name ?? $file->getValue('name')))
				->withHeader('Content-Length', $file->getValue('size'))
				->withHeader('Pragma', 'public')
				->withBody(Stream::create(FileStream::openRead($file->getValue('native'))->stream()));
		});
	}

	public function persistent(string $directory = null): string {
		$this->directory->create($directory = '/persistent/' . $directory);
		return $this->directory->prefix($directory);
	}

	public function remove(string $path) {
		try {
			rmrdir($path);
		} catch (Throwable $exception) {
			$this->logger->error($exception);
		}
	}
}
