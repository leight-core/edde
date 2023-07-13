<?php
declare(strict_types=1);

namespace Edde\Auth\Rpc;

use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Session\SessionTrait;

class LogoutRpcHandler extends AbstractRpcHandler {
	use SessionTrait;

	public function handle(SmartDto $request): ?SmartDto {
		$this->session->remove('user');
		return null;
	}
}
