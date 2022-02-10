<?php
declare(strict_types=1);

namespace Edde\Image;

use Edde\File\Dto\FileDto;
use Edde\File\FileGcServiceTrait;
use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Job\AbstractJobService;
use Edde\Job\IJob;
use Edde\Query\Dto\Query;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Repository\Exception\RepositoryException;
use Throwable;
use function in_array;
use function microtime;

class ImageJobService extends AbstractJobService {
	use FileGcServiceTrait;
	use FileRepositoryTrait;
	use FileMapperTrait;

	/**
	 * @param IJob $job
	 *
	 * @throws DuplicateEntryException
	 * @throws RepositoryException
	 * @throws Throwable
	 */
	protected function handle(IJob $job) {
		static $allowed = ['image/jpeg'];

		$progress = $job->getProgress();
		$progress->onStart();

		/** @var $file FileDto */
		foreach ($this->fileMapper->map($this->fileRepository->execute((new Query())->withFilter(['pathEndLike' => '/image.raw']))) as $file) {
			if (!in_array($file->mime, $allowed)) {
				$this->logger->warning(sprintf('Image [%s] with invalid mime type [%s].', $file->path, $file->mime));
				$this->fileRepository->change([
					'id'  => $file->id,
					'ttl' => microtime(true) - 1,
				]);
				continue;
			}

		}

		$this->fileGcService->async();
	}
}
