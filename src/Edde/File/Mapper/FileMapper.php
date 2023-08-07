<?php
declare(strict_types=1);

namespace Edde\File\Mapper;

use Edde\File\Dto\FileDto;
use Edde\Mapper\AbstractMapper;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;

class FileMapper extends AbstractMapper {
	/**
	 * @param       $item
	 * @param null  $params
	 *
	 * @return FileDto
	 *
	 * @throws ItemException
	 * @throws SkipException
	 */
	public function item($item, $params = null) {
		return $this->dtoService->fromArray(FileDto::class, [
			'id'      => $item->id,
			'path'    => $item->path,
			'name'    => $item->name,
			'mime'    => $item->mime,
			'native'  => $item->native,
			'size'    => $item->size,
			'userId' => $item->user_id,
			'created' => $this->isoDateNull($item->created),
			'updated' => $this->isoDateNull($item->updated),
			'ttl'     => $item->ttl,
		]);
	}
}
