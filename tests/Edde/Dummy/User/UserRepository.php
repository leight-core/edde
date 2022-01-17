<?php
declare(strict_types=1);

namespace Edde\Dummy\User;

use Edde\Repository\AbstractRepository;
use Edde\User\Repository\IUserRepository;

class UserRepository extends AbstractRepository implements IUserRepository {
	public function findByLogin($login) {
		return null;
	}

	public function updateSettings($userId, $settings) {
		return null;
	}
}
