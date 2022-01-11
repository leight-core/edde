<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\File\Dto\FileDto;
use Edde\File\FileServiceTrait;
use Edde\Job\AbstractJobService;
use Edde\Job\IJob;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use function array_diff_key;
use function array_filter;
use function array_flip;
use function array_keys;
use function implode;

abstract class AbstractImportService extends AbstractJobService implements IImportService {
	use FileServiceTrait;

	/**
	 * Do an import from the given job.
	 *
	 * @param IJob $job
	 *
	 * @return mixed
	 */
	protected function handle(IJob $job) {
		if (is_object($file = $job->getParams())) {
			$file = $file->file;
		}
		$params = $job->getParams() ?? [];
		return $this->fileService->useFile($file, function (FileDto $fileDto) use ($job, $params) {
			$job->getProgress()->check();
			$this->import($fileDto->native, $params, $job->getProgress());
		});
	}

	/**
	 * @param mixed $itemDto
	 * @param array $required
	 *
	 * @return mixed|null
	 *
	 * @throws ItemException
	 * @throws SkipException
	 */
	public function check($itemDto, array $required) {
		$empty = array_filter($itemDto->item);
		if (empty($empty)) {
			throw new SkipException('Source has an empty data (falsey).', [
				'expected' => implode(', ', array_keys($itemDto->item)),
				'current'  => implode(', ', array_keys($itemDto->source ?? [])),
			]);
		}
		if (!empty($diff = array_diff_key(array_flip($required), $empty))) {
			throw new ItemException('Missing required fields.', ['missing' => array_keys($diff)], 'import.validation.missing-values');
		}
		return $itemDto->item;
	}
}
