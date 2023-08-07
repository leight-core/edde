<?php
declare(strict_types=1);

namespace Edde\Mapper;

use Edde\Dto\DtoServiceTrait;
use Edde\Dto\SmartServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Mapper\Exception\SkipException;
use Generator;
use function iterator_to_array;

abstract class AbstractMapper implements IMapper {
	use MapperUtilsTrait;
	use LoggerTrait;
	use DtoServiceTrait;
	use SmartServiceTrait;

	/**
	 * Empty constructor is here to enable children implement parent::__construct without worrying if
	 * it exists.
	 */
	public function __construct() {
	}

	public function map(iterable $source, $params = null): array {
		return iterator_to_array($this->stream($source, $params));
	}

	public function stream(iterable $source, $params = null): Generator {
		foreach ($source as $item) {
			try {
				yield $this->item($item, $params);
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
	}
}
