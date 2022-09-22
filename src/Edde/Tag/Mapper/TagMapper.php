<?php
declare(strict_types=1);

namespace Edde\Tag\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Tag\Dto\TagDto;

class TagMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->dtoService->fromArray(TagDto::class, [
			'id'    => $item->id,
			'tag'   => $item->tag,
			'sort'  => $item->sort,
			'group' => $item->group,
		]);
	}
}
