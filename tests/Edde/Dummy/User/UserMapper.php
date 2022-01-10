<?php
declare(strict_types=1);

namespace Edde\Dummy\User;

use Edde\Mapper\AbstractMapper;
use Edde\User\Mapper\IUserMapper;

class UserMapper extends AbstractMapper implements IUserMapper {
	public function item($item, array $params = []) {
		return null;
	}
}
