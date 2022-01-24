<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\File\Dto\FileDto;
use Edde\File\Dto\FileFilterDto;
use Edde\File\Dto\FileOrderByDto;
use Edde\File\Mapper\FileMapperTrait;
use Edde\File\Repository\FileRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

class FilesEndpoint extends AbstractQueryEndpoint {
	use FileRepositoryTrait;
	use FileMapperTrait;

	/**
	 * @param Query<FileOrderByDto, FileFilterDto> $query
	 *
	 * @return QueryResult<FileDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->fileRepository->toResult($query, $this->fileMapper);
	}
}
