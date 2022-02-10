<?php
declare(strict_types=1);

namespace Edde\User\Mapper;

use Edde\Bridge\User\UserDto;
use Edde\Mapper\AbstractMapper;

abstract class AbstractUserMapper extends AbstractMapper implements IUserMapper {
	public function item($item) {
		return $this->dtoService->fromArray(UserDto::class, $this->toUser($item));
	}

	abstract protected function toUser($item): array;
}
