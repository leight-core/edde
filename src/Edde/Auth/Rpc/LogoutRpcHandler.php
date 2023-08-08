<?php
declare(strict_types=1);

namespace Edde\Auth\Rpc;

use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Session\SessionTrait;

class LogoutRpcHandler extends AbstractRpcHandler {
	use SessionTrait;

	protected $isMutator = true;

	public function handle(SmartDto $request) {
		$this->session->remove('user');
	}
}
