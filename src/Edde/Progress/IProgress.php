<?php
declare(strict_types=1);

namespace Edde\Progress\;

use Edde\Progress\Dto\ItemDto;
use Throwable;

/**
 * When there is need to track progress of something (like an import) with event log,
 * this interface should be used).
 */
interface IProgress {
	const LOG_INFO = 0;
	const LOG_WARNING = 1;
	const LOG_ERROR = 2;

	/**
	 * Called on the beginning of the job (when total number of items is known).
	 *
	 * @param int $total
	 */
	public function onStart(int $total = 1): void;

	/**
	 * Called when the given item has been processed.
	 *
	 * This method could be called in total of count used in onCount method.
	 *
	 * @param ItemDto $itemDto
	 */
	public function onProgress(ItemDto $itemDto): void;

	/**
	 * When a job is done and returned to JobManager this method is called.
	 */
	public function onDone($result): void;

	/**
	 * Called when an item cannot be processed (but job is still running).
	 *
	 * @param Throwable $throwable
	 * @param mixed     $itemDto
	 */
	public function onError(Throwable $throwable, ItemDto $itemDto): void;

	/**
	 * Hook called when some nasty error happens (thus whole job fails without recovery).
	 *
	 * @param Throwable $throwable
	 */
	public function onFailure(Throwable $throwable): void;

	/**
	 * Do job checks, typically check for the job interruption (could be also internally called inside other methods).
	 */
	public function check(): void;

	/**
	 * Log something during job (it's not necessarily mapping between one log row to one job item; reference
	 * is grouping element).
	 *
	 * @param int          $level     log level, should be used levels of IProgress
	 * @param string       $message   what to log
	 * @param ItemDto|null $item      optional item used in the job (for example import item row, ...)
	 * @param string|null  $type      optional error type to define type of an error (thus "typing" item array)
	 * @param string|null  $reference reference to processed item (could be index to data source, an ID, whatever)
	 *
	 * @return mixed
	 */
	public function log(int $level, string $message, ItemDto $itemDto = null, string $type = null, string $reference = null);
}
