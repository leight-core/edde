<?php
declare(strict_types=1);

namespace Edde\Profiler\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Profiler\Dto\ProfilerDto;

class ProfilerMapper extends AbstractMapper {
	public function item($item, array $params = []) {
		return $this->dtoService->fromArray(ProfilerDto::class, [
			'id'      => $item->id,
			'name'    => $item->name,
			'stamp'   => $item->stamp,
			'runtime' => $item->runtime,
		]);
	}
}
