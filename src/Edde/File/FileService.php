<?php
declare(strict_types=1);

namespace Edde\File;

use Dibi\Exception;
use Edde\Dto\DtoServiceTrait;
use Edde\File\Dto\EnsureDto;
use Edde\File\Dto\FileDto;
use Edde\File\Dto\GcResultDto;
use Edde\File\Exception\FileNotFoundException;
use Edde\File\Exception\FileNotReadableException;
use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Math\RandomServiceTrait;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\Exception\RepositoryException;
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
	use FileMapperTrait;
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
	public function accept(string $file, string $path, string $name, float $ttl = null): FileDto {
		return FileStream::openRead($_FILES[$file]['tmp_name'])
			->use(function (IStream $stream) use ($path, $name, $ttl) {
				return $this->store($stream, $path, $name, $ttl, $this->currentUserService->optionalId());
			});
	}

	/**
	 * @inheritdoc
	 */
	public function chunk(IStream $stream, string $name, ?string $userId = null): FileDto {
		$file = $this->file('/chunk', $name, 'application/vnd.chunk', 60 * 5, $userId);

		FileStream::openAppend($file->native)->useToStream($stream);

		return $this->fileMapper->item($this->fileRepository->change([
			'id'   => $file->id,
			'size' => $this->sizeOf($file->native),
		]));
	}

	public function commit(string $chunk, string $path, string $name = null, bool $replace = false): FileDto {
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

	public function gc(bool $force = false): GcResultDto {
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
					$this->fileRepository->deleteByNative($file->native);
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
	public function file(string $path, string $name, string $mime, float $ttl = null, ?string $userId = null): FileDto {
		$native = $this->directory->prefix('files/' . str_replace('-', '/', $uuid = $this->uuidService->uuid4()) . '/' . $uuid);
		$file = $this->fileMapper->item($this->fileRepository->ensure($this->dtoService->fromArray(EnsureDto::class, [
			'path'   => $path,
			'name'   => $name,
			'mime'   => $mime,
			'userId' => $userId,
			'size'   => -1,
			'ttl'    => $ttl,
			'native' => $this->directory->normalize($native),
		])));
		$this->directory->create(dirname($this->directory->base($file->native)));
		touch($file->native);
		return $file;
	}

	/**
	 * @inheritdoc
	 */
	public function store(IStream $stream, string $path, string $name, float $ttl = null, ?string $userId = null): FileDto {
		try {
			$fileDto = $this->file(rtrim($path, '/'), ltrim($name, '/'), 'application/octet-stream', $ttl, $userId);
			FileStream::openWrite($fileDto->native)->useToStream($stream);
			return $this->refresh($fileDto->id);
		} catch (Throwable $exception) {
			$this->logger->error($exception);
			throw $exception;
		}
	}

	public function refresh(string $fileId): FileDto {
		$fileDto = $this->fileMapper->item($this->fileRepository->find($fileId));
		return $this->fileMapper->item($this->fileRepository->change([
			'id'   => $fileDto->id,
			'mime' => $this->mimeService->detect($fileDto->native),
			'size' => $this->sizeOf($fileDto->native),
		]));
	}

	public function useFile(string $fileId, callable $callback) {
		return $callback($this->fileMapper->item($this->fileRepository->find($fileId)));
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
	 * @throws RepositoryException
	 */
	public function consumeFile(string $fileId, callable $callback) {
		$file = $this->fileMapper->item($this->fileRepository->find($fileId));
		try {
			return $callback($file);
		} finally {
			$this->delete($file->native);
			$this->fileRepository->delete($fileId);
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
		return $this->useFile($fileId, function (FileDto $fileDto) use ($response, $name) {
			return $response
				->withHeader('Content-Type', $fileDto->mime)
				->withHeader('Cache-Control', 'private, max-age=0, must-revalidate')
				->withHeader('Content-Disposition', 'inline; filename=' . rawurlencode($name ?? $fileDto->name))
				->withHeader('Content-Length', $fileDto->size)
				->withHeader('Pragma', 'public')
				->withBody(Stream::create(FileStream::openRead($fileDto->native)->stream()));
		});
	}

	public function persistent(string $directory = null): string {
		$this->directory->create($directory = '/persistent/' . $directory);
		return $this->directory->prefix($directory);
	}

	public function remove(string $path) {
		rmrdir($path);
	}
}
