<?php
declare(strict_types=1);

namespace Edde\File\Mapper;

use Edde\File\Dto\FileDto;
use Edde\Mapper\AbstractMapper;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\User\Mapper\UserMapperTrait;
use Edde\User\Repository\UserRepositoryTrait;

class FileMapper extends AbstractMapper {
	use UserRepositoryTrait;
	use UserMapperTrait;

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
			'user'    => $item->user_id ? $this->userMapper->item($this->userRepository->find($item->user_id), $params) : null,
			'created' => $this->isoDateNull($item->created),
			'updated' => $this->isoDateNull($item->updated),
			'ttl'     => $item->ttl,
		]);
	}
}
