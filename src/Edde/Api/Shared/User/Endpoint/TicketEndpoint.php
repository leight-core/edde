<?php
declare(strict_types=1);

namespace Edde\Api\Shared\User\Endpoint;

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
	 * @return SessionDto|void
	 *
	 * @throws ItemException
	 * @throws SkipException
	 * @throws UserNotSelectedException
	 */
	public function get(): ?SessionDto {
		if ($this->currentUserService->isSelected()) {
			return $this->sessionMapper->item($this->currentUserService->requireUser());

		}
		return null;
	}
}
