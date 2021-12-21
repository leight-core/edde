<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Progress\IProgress;
use Generator;

/**
 * General purpose importer; it could be used as a stream when iterated to get results per an input item.
 */
interface IImporter {
	/**
	 * @param iterable  $source   source of this import; should provide import DTOs
	 * @param IProgress $progress progress to see how the import is going; progress life cycle must be controlled outside of import itself
	 *
	 * @return mixed|Generator
	 */
	public function import(iterable $source, IProgress $progress): Generator;

	/**
	 * Run the import without worrying about the output.
	 *
	 * @param iterable  $source
	 * @param IProgress $progress
	 */
	public function run(iterable $source, IProgress $progress): void;
}
