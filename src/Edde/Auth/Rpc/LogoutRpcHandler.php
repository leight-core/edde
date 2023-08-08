<?php
declare(strict_types=1);

namespace Edde\Auth\Rpc;

use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Rpc\Utils\WithMutator;
use Edde\Session\SessionTrait;

class LogoutRpcHandler extends AbstractRpcHandler {
	use SessionTrait;

	use WithMutator;

	public function handle(SmartDto $request) {
		$this->session->remove('user');
	}
}
