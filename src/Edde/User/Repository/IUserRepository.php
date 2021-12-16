<?php
declare(strict_types=1);

namespace Edde\User\Repository;

use Edde\Repository\IRepository;

interface IUserRepository extends IRepository {
	public function findByLogin($login);
}
