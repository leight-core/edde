<?php
declare(strict_types=1);

namespace Edde\Tag\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Tag\Dto\TagDto;

class TagMapper extends AbstractMapper {
	public function item($item) {
		return $this->dtoService->fromArray(TagDto::class, [
			'id'    => $item->id,
			'code'  => $item->code,
			'label' => $item->label,
			'sort'  => $item->sort,
			'group' => $item->group,
		]);
	}
}
