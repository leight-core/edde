<?php
declare(strict_types=1);

namespace Edde\User\Repository;

use Edde\Database\Repository\IRepository;

interface IUserRepository extends IRepository {
	public function findByLogin(string $login);
}
