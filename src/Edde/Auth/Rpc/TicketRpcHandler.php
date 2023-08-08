<?php
declare(strict_types=1);

namespace Edde\Auth\Rpc;

use Edde\Auth\Mapper\SessionMapperTrait;
use Edde\Auth\Schema\SessionSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Rpc\Utils\WithOptionalResponseSchema;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Repository\UserRepositoryTrait;

/**
 * Checks if there is an active user (return session or null).
 */
class TicketRpcHandler extends AbstractRpcHandler {
	use CurrentUserServiceTrait;
	use UserRepositoryTrait;
	use SessionMapperTrait;

	use WithOptionalResponseSchema;

	protected $responseSchema = SessionSchema::class;

	public function handle(SmartDto $request) {
		if ($this->currentUserService->isSelected()) {
			return $this->sessionMapper->item($this->currentUserService->requireUser());
		}
	}
}
