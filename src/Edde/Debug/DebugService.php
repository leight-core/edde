<?php
declare(strict_types=1);

namespace Edde\Debug;

use Edde\Debug\Exception\DebugException;
use Edde\File\Dto\FileDto;
use Edde\File\Service\FileServiceTrait;
use Edde\Stream\TempStream;
use Edde\Uuid\Service\UuidServiceTrait;
use Marsh\User\CurrentUserTrait;
use Throwable;
use Tracy\Debugger;
use function date;
use function ob_get_clean;
use function ob_start;

class DebugService {
	use FileServiceTrait;
	use CurrentUserTrait;
	use UuidServiceTrait;

	protected $lock = false;

	/**
	 * Render throwable into standalone page with some information. Be notice that this
	 * method is quite heavy and returned string could take quite bit of memory.
	 *
	 * @param Throwable $throwable
	 *
	 * @return string
	 */
	public function render(Throwable $throwable): string {
		ob_start();
		Debugger::getBlueScreen()->render($throwable);
		return ob_get_clean();
	}

	/**
	 * Save the log as a file; this method could die if there is some low-level system exception. Thus be careful as this method
	 * could take down the app. In general, this should **not** be used in user space as some logger should use this service.
	 *
	 * @param Throwable $throwable
	 *
	 * @return FileDto
	 */
	public function save(Throwable $throwable): FileDto {
		if ($this->lock) {
			throw new DebugException('Recursive exception occurred.', 0, $throwable);
		}
		$this->lock = true;
		try {
			return $this->fileService->store(
				TempStream::create($this->render($throwable)),
				'/logs',
				date('Y-m-d H:i:s') . '-' . $this->uuidService->uuid4() . '.html',
				/**
				 * Store the file for a few days.
				 */
				60 * 60 * 24 * 7,
				$this->currentUser->optionalId()
			);
		} finally {
			$this->lock = false;
		}
	}

	public function safeSave(Throwable $throwable): ?FileDto {
		try {
			return $this->save($throwable);
		} catch (Throwable $throwable) {
			/**
			 * Bad luck, swallow whatever we have.
			 */
			return null;
		}
	}
}
