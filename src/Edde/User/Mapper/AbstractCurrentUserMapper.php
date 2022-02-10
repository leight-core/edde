<?php
declare(strict_types=1);

namespace Edde\User\Mapper;

use Edde\Bridge\User\CurrentUser;
use Edde\Mapper\AbstractMapper;

abstract class AbstractCurrentUserMapper extends AbstractMapper implements ICurrentUserMapper {
	public function item($item) {
		if (!$item) {
			return null;
		}
		return $this->dtoService->fromArray(CurrentUser::class, $this->toUser($item, $params));
	}

	abstract protected function toUser($item, array $params = []): array;
}
