<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

use Edde\Job\Dto\Log\JobLogDto;
use Edde\Mapper\AbstractMapper;

class JobLogMapper extends AbstractMapper {
	public function item($item, array $params = []) {
		return $this->dtoService->fromArray(JobLogDto::class, [
			'id'        => $item->id,
			'level'     => $item->level,
			'item'      => json_decode($item->item ?? 'null'),
			'stamp'     => $item->stamp,
			'type'      => $item->type,
			'message'   => $item->message,
			'reference' => $item->reference,
		]);
	}
}
