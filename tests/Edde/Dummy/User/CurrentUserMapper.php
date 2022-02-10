<?php
declare(strict_types=1);

namespace Edde\Dummy\User;

use Edde\User\Mapper\AbstractCurrentUserMapper;

class CurrentUserMapper extends AbstractCurrentUserMapper {
	protected function toUser($item): array {
		return [];
	}
}
