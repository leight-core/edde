<?php
declare(strict_types=1);

namespace Edde\Import\Template\Mapper;

use ClanCats\Hydrahon\Query\Sql\Exception;
use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Import\Template\Dto\ImportTemplateDto;
use Edde\Mapper\AbstractMapper;
use Edde\Repository\Exception\RepositoryException;

class ImportTemplateMapper extends AbstractMapper {
	use FileMapperTrait;
	use FileRepositoryTrait;

	/**
	 * @param       $item
	 * @param array $params
	 *
	 * @return ImportTemplateDto
	 *
	 * @throws Exception
	 * @throws RepositoryException
	 */
	public function item($item, array $params = []) {
		return $this->dtoService->fromArray(ImportTemplateDto::class, [
			'id'     => $item->id,
			'name'   => $item->name,
			'hash'   => $item->hash,
			'fileId' => $item->file_id,
			'file'   => $this->fileMapper->item($this->fileRepository->find($item->file_id)),
		]);
	}
}
