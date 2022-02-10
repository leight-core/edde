<?php
declare(strict_types=1);

namespace Edde\Dummy\User;

use Edde\User\Mapper\AbstractUserMapper;

class UserMapper extends AbstractUserMapper {
	protected function toUser($item): array {
		return [];
	}
}
