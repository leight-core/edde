<?php
declare(strict_types=1);

namespace Edde\Image\Mapper;

use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Image\Dto\ImageDto;
use Edde\Mapper\AbstractMapper;

class ImageMapper extends AbstractMapper {
	use FileRepositoryTrait;
	use FileMapperTrait;

	public function item($item, array $params = []) {
		return $this->dtoService->fromArray(ImageDto::class, [
			'id'         => $item->id,
			'preview'    => $this->fileMapper->item($this->fileRepository->find($item->preview_id)),
			'previewId'  => $item->preview_id,
			'original'   => $this->fileMapper->item($this->fileRepository->find($item->original_id)),
			'originalId' => $item->original_id,
			'gallery'    => $item->gallery,
			'stamp'      => $this->isoDateNull($item->stamp),
		]);
	}
}
