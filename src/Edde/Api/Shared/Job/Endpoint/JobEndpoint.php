<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Endpoint;

use ClanCats\Hydrahon\Query\Sql\Exception;
use Edde\Job\Dto\JobDto;
use Edde\Job\Mapper\JobDtoMapperTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Repository\Exception\RepositoryException;
use Edde\Rest\Endpoint\AbstractFetchEndpoint;
use Edde\Rest\Exception\RestException;

/**
 * @description Retrieve the selected jobs.
 * @query       jobId
 */
class JobEndpoint extends AbstractFetchEndpoint {
	use JobRepositoryTrait;
	use JobDtoMapperTrait;

	/**
	 * @return JobDto
	 *
	 * @throws ItemException
	 * @throws RestException
	 * @throws SkipException
	 * @throws Exception
	 * @throws RepositoryException
	 */
	public function get(): JobDto {
		return $this->jobDtoMapper->item($this->jobRepository->find($this->param('jobId')));
	}
}
