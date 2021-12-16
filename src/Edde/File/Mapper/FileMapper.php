<?php
declare(strict_types=1);

namespace Edde\File\Mapper;

use Edde\File\Dto\FileDto;
use Edde\Mapper\AbstractMapper;
use Edde\User\Repository\UserRepositoryTrait;
use Marsh\User\Mapper\UserMapperTrait;

class FileMapper extends AbstractMapper {
	use UserRepositoryTrait;
	use UserMapperTrait;

	/**
	 * @param       $item
	 * @param array $params
	 *
	 * @return FileDto
	 */
	public function item($item, array $params = []) {
		return $this->dtoService->fromArray(FileDto::class, [
			'id'      => $item->id,
			'path'    => $item->path,
			'name'    => $item->name,
			'mime'    => $item->mime,
			'native'  => $item->native,
			'size'    => $item->size,
			'user'    => $item->user_id ? $this->userMapper->item($this->userRepository->find($item->user_id)) : null,
			'created' => $this->isoDateNull($item->created),
			'updated' => $this->isoDateNull($item->updated),
			'ttl'     => $item->ttl,
		]);
	}
}
