<?php
declare(strict_types=1);

namespace Edde\Image;

use Edde\Dto\DtoServiceTrait;
use Edde\File\Dto\FileDto;
use Edde\File\FileGcServiceTrait;
use Edde\File\FileServiceTrait;
use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Image\Dto\CreateDto;
use Edde\Image\Repository\ImageRepositoryTrait;
use Edde\Job\AbstractJobService;
use Edde\Job\IJob;
use Edde\Query\Dto\Query;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\Exception\RepositoryException;
use Edde\Stream\FileStream;
use Throwable;
use function microtime;
use function str_replace;

class ImageJobService extends AbstractJobService {
	use FileGcServiceTrait;
	use FileRepositoryTrait;
	use FileMapperTrait;
	use FileServiceTrait;
	use ImageServiceTrait;
	use ImageRepositoryTrait;
	use DtoServiceTrait;

	/**
	 * @param IJob $job
	 *
	 * @throws DuplicateEntryException
	 * @throws RepositoryException
	 * @throws Throwable
	 */
	protected function handle(IJob $job) {
		$query = (new Query())->withFilter(['pathEndLike' => '/image.raw']);

		$progress = $job->getProgress();
		$progress->onStart($this->fileRepository->total($query));

		/** @var $file FileDto */
		foreach ($this->fileMapper->map($this->fileRepository->execute($query)) as $file) {
			try {
				$this->imageService->convert($file->native, 'jpeg');
				$original = $this->fileService->store(
					FileStream::openRead($file->native),
					str_replace('/image.raw', '/original', $file->path),
					$file->name,
					null,
					$file->user->id
				);
				/**
				 * Prevent users for uploading some huuuge shits, so images will be kept in some sane dimensions.
				 */
				$this->imageService->resize($original->native, 2400, 2400);
				$this->fileService->refresh($original->id);
				$preview = $this->fileService->store(
					FileStream::openRead($file->native),
					str_replace('/image.raw', '', $file->path),
					$file->name,
					null,
					$file->user->id
				);
				/**
				 * Image preview will be quite small to keep the size small too.
				 */
				$this->imageService->resize($preview->native, 200, 200);
				$this->fileService->refresh($preview->id);

				$this->imageRepository->create($this->dtoService->fromArray(CreateDto::class, [
					'gallery'    => str_replace('/image.raw', '', $file->path),
					'originalId' => $original->id,
					'previewId'  => $preview->id,
					'userId'     => $file->user->id,
				]));
				$progress->onProgress();
			} catch (Throwable $exception) {
				$progress->onError($exception);
				$this->logger->error($exception);
			}

			/**
			 * Mark raw file as stale (so it will be removed).
			 */
			$this->fileRepository->change([
				'id'  => $file->id,
				'ttl' => microtime(true) - 1,
			]);
		}

		$this->fileGcService->async();
	}
}
