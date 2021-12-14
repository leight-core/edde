<?php
declare(strict_types=1);

namespace Edde\Mapper;

use Edde\Dto\DtoServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Mapper\Exception\SkipException;

abstract class AbstractMapper implements IMapper {
	use MapperUtilsTrait;
	use LoggerTrait;
	use DtoServiceTrait;

	/**
	 * Empty constructor is here to enable children implement parent::__construct without worrying if
	 * it exists.
	 */
	public function __construct() {
	}

	public function map(iterable $source): array {
		$map = [];
		foreach ($source as $item) {
			try {
				$map[] = $this->item($item);
			} catch (SkipException $exception) {
				/**
				 * Swallowing exceptions is road to hell, thus it's necessary to log
				 * also SkipExceptions.
				 */
				$this->logger->debug($exception->getMessage() ?? 'Skipped by an exception.', [
					'source' => $item,
				]);
			}
		}
		return $map;
	}
}
