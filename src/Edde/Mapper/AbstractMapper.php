<?php
declare(strict_types=1);

namespace Edde\Mapper;

use Edde\Dto\DtoServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Mapper\Exception\SkipException;
use Edde\Reflection\ReflectionServiceTrait;
use Generator;
use function iterator_to_array;

abstract class AbstractMapper implements IMapper {
	use MapperUtilsTrait;
	use LoggerTrait;
	use DtoServiceTrait;
	use ReflectionServiceTrait;

	/**
	 * Empty constructor is here to enable children implement parent::__construct without worrying if
	 * it exists.
	 */
	public function __construct() {
	}

	public function map(iterable $source): array {
		return iterator_to_array($this->stream($source));
	}

	public function stream(iterable $source): Generator {
//		$dto = $this->reflectionService->toClass(static::class)->getResponseClassOf('item') && $this->reflectionService->toClass($dto)->is(IDto::class);
		foreach ($source as $item) {
			try {
				yield $this->item($item);
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
