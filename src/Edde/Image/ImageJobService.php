<?php
declare(strict_types=1);

namespace Edde\Image;

use Edde\File\Dto\FileDto;
use Edde\File\FileGcServiceTrait;
use Edde\File\FileServiceTrait;
use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Job\AbstractJobService;
use Edde\Job\IJob;
use Edde\Query\Dto\Query;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\Exception\RepositoryException;
use Edde\Stream\FileStream;
use Throwable;
use function in_array;
use function microtime;
use function sprintf;
use function str_replace;

class ImageJobService extends AbstractJobService {
	use FileGcServiceTrait;
	use FileRepositoryTrait;
	use FileMapperTrait;
	use FileServiceTrait;

	/**
	 * @param IJob $job
	 *
	 * @throws DuplicateEntryException
	 * @throws RepositoryException
	 * @throws Throwable
	 */
	protected function handle(IJob $job) {
		static $allowed = [
			'image/jpeg',
			'image/png',
		];

		$progress = $job->getProgress();
		$progress->onStart();

		/** @var $file FileDto */
		foreach ($this->fileMapper->map($this->fileRepository->execute((new Query())->withFilter(['pathEndLike' => '/image.raw']))) as $file) {
			if (!in_array($file->mime, $allowed)) {
				$this->logger->warning(sprintf('Image [%s] with invalid mime type [%s].', $file->path, $file->mime));
				continue;
			}
			$this->fileService->store(
				FileStream::openRead($file->native),
				str_replace('/image.raw', '/original', $file->path),
				$file->name,
				null,
				$file->user->id
			);
			$this->fileRepository->change([
				'id'  => $file->id,
				'ttl' => microtime(true) - 1,
			]);
		}

		$this->fileGcService->async();
	}
}
