<?php
declare(strict_types=1);

namespace Edde\Api\Shared\User\Endpoint;

use Edde\Dto\Common\LoginRequest;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Password\PasswordServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;
use Edde\Rest\Exception\ClientException;
use Edde\Session\Dto\SessionDto;
use Edde\Session\SessionMapperTrait;
use Edde\Session\SessionTrait;
use Edde\User\Mapper\CurrentUserMapperTrait;
use Edde\User\Repository\UserRepositoryTrait;

/**
 * @description Do an user login.
 */
class LoginEndpoint extends AbstractMutationEndpoint {
	use SessionTrait;
	use UserRepositoryTrait;
	use CurrentUserMapperTrait;
	use SessionMapperTrait;
	use PasswordServiceTrait;

	/**
	 * @param LoginRequest $loginRequest
	 *
	 * @return SessionDto
	 *
	 * @throws ClientException
	 * @throws ItemException
	 * @throws SkipException
	 */
	public function post(LoginRequest $loginRequest): SessionDto {
		if (!($user = $this->userRepository->findByLogin($loginRequest->login))) {
			throw new ClientException('Unknown login', 400);
		}
		if (!$this->passwordService->isMatch($loginRequest->password, $user->password ?? null)) {
			throw new ClientException('Unknown login', 400);
		}
		$this->session->set('user', $user->id);
		return $this->sessionMapper->item($this->currentUserMapper->item($user));
	}
}
