<?php
declare(strict_types=1);

namespace Edde\Progress;

use Edde\Progress\Dto\ItemDto;
use Throwable;

class NoProgress implements IProgress {
	public function onStart(int $total = 1): void {
	}

	public function onProgress(ItemDto $itemDto): void {
	}

	public function onDone($result): void {
	}

	public function onError(Throwable $throwable, ItemDto $itemDto): void {
	}

	public function onFailure(Throwable $throwable): void {
	}

	static public function ensure(?IProgress $progress) {
		return $progress ?: new self();
	}

	public function check(): void {
	}

	public function log(int $level, string $message, ItemDto $itemDto = null, string $type = null, string $reference = null) {
	}
}
