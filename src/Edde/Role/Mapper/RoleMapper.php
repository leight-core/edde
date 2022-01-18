<?php
declare(strict_types=1);

namespace Edde\Role\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Role\Dto\RoleDto;

class RoleMapper extends AbstractMapper {
	public function item($item, array $params = []) {
		return $this->dtoService->fromArray(RoleDto::class, [
			'id'   => $item->id,
			'name' => $item->name,
		]);
	}
}
