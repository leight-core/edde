<?php
declare(strict_types=1);

namespace Edde\User\Api;

use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Session\Dto\SessionDto;
use Edde\Session\SessionMapperTrait;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Exception\UserNotSelectedException;
use Edde\User\Repository\UserRepositoryTrait;

/**
 * @description Retrieve current user or throw an error if not available (useful for user session checks).
 */
class TicketEndpoint extends AbstractFetchEndpoint {
	use CurrentUserServiceTrait;
	use UserRepositoryTrait;
	use SessionMapperTrait;

	/**
	 * @return SessionDto
	 *
	 * @throws ItemException
	 * @throws SkipException
	 * @throws UserNotSelectedException
	 */
	public function get(): SessionDto {
		if ($this->currentUserService->isSelected()) {
			return $this->sessionMapper->item($this->currentUserService->requireUser());

		}
		/**
		 * When an user does not have a session, treat him as public (common) user.
		 */
		return $this->sessionMapper->item($this->currentUserService->publicUser());
	}
}
