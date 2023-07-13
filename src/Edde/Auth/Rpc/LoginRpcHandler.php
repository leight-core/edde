<?php
declare(strict_types=1);

namespace Edde\Auth\Rpc;

use Edde\Auth\Mapper\SessionMapperTrait;
use Edde\Auth\Schema\LoginSchema;
use Edde\Auth\Schema\SessionSchema;
use Edde\Dto\SmartDto;
use Edde\Password\PasswordServiceTrait;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Rpc\Exception\RpcException;
use Edde\Session\SessionTrait;
use Edde\User\Repository\UserRepositoryTrait;

class LoginRpcHandler extends AbstractRpcHandler {
	use SessionTrait;
	use UserRepositoryTrait;
	use PasswordServiceTrait;
	use SessionMapperTrait;

	protected $requestSchema = LoginSchema::class;
	protected $responseSchema = SessionSchema::class;

	public function handle(SmartDto $request): ?SmartDto {
		if (!($user = $this->userRepository->findByLogin($request->getValue('login')))) {
			throw new RpcException('Unknown login', 400);
		}
		if (!$this->passwordService->isMatch($request->getValue('password'), $user->password ?? null)) {
			throw new RpcException('Unknown login', 400);
		}
		$this->session->set('user', $user->id);
		/**
		 * Current user mapper normalizes user's data from DB.
		 */
		return $this->sessionMapper->item($user);
	}
}
