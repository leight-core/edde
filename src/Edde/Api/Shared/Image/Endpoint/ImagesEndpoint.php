<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Image\Endpoint;

use Edde\Image\Dto\ImageDto;
use Edde\Image\Dto\ImageFilterDto;
use Edde\Image\Dto\ImageOrderByDto;
use Edde\Image\Mapper\ImageMapperTrait;
use Edde\Image\Repository\ImageRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

class ImagesEndpoint extends AbstractQueryEndpoint {
	use ImageRepositoryTrait;
	use ImageMapperTrait;

	/**
	 * @param Query<ImageOrderByDto, ImageFilterDto> $query
	 *
	 * @return QueryResult<ImageDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->imageRepository->toResult($query, $this->imageMapper);
	}
}
