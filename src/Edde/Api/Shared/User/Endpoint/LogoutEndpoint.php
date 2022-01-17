<?php
declare(strict_types=1);

namespace Edde\Api\Shared\User\Endpoint;

use Edde\Rest\Endpoint\AbstractMutationEndpoint;
use Edde\Session\SessionTrait;

/**
 * @description Do an user logout (delete current session).
 */
class LogoutEndpoint extends AbstractMutationEndpoint {
	use SessionTrait;

	public function delete(): void {
		$this->session->remove('user');
	}
}
