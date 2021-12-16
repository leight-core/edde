<?php
declare(strict_types=1);

namespace Edde\Log;

use Edde\Debug\DebugServiceTrait;
use Edde\Log\Dto\Create\CreateDto;
use Edde\Log\Repository\LogRepositoryTrait;
use Edde\User\CurrentUserServiceTrait;
use Throwable;

/**
 * @Injectable(lazy=true)
 */
class DatabaseLogger extends AbstractLogger {
	use LogRepositoryTrait;
	use CurrentUserServiceTrait;
	use DebugServiceTrait;

	public function log($level, $message, array $context = []) {
		$trace = null;
		if ($message instanceof Throwable) {
			$this->debugService->safeSave($message);
			$trace = $message->getTraceAsString();
			$message = $message->getMessage();
		}
		$traceId = $context['traceId'] ?? $this->traceService->trace();
		$referenceId = $context['referenceId'] ?? $this->traceService->reference();
		$tags = $context['tags'] ?? [];
		unset($context['referenceId'], $context['traceId'], $context['tags']);
		try {
			/**
			 * CreateDto::create is used to prevent dependency from the low-level service to the
			 * quite high-level one.
			 *
			 * Also, one should be sure that all data are filled here.
			 */
			$this->logRepository->create(CreateDto::create([
				'log'         => $message,
				'type'        => $level,
				'traceId'     => $traceId,
				'referenceId' => $referenceId,
				'userId'      => $this->currentUserService->optionalId(),
				'trace'       => $trace,
				'context'     => empty($context) ? null : $context,
				'tags'        => $tags,
			]));
		} catch (Throwable $throwable) {
			/**
			 * Bad luck, we're blind. Logger should not kill the running application.
			 *
			 * At least we could try to save the exception.
			 */
			$this->debugService->safeSave($throwable);
		}
	}
}
