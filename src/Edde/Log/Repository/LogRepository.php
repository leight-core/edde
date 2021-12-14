<?php
declare(strict_types=1);

namespace Edde\Log\Repository;

use ClanCats\Hydrahon\Query\Sql\Select;
use ClanCats\Hydrahon\Query\Sql\SelectBase;
use DateTime;
use Dibi\Exception;
use Edde\Log\Dto\Create\CreateDto;
use Edde\Log\Dto\LogFilterDto;
use Edde\Query\Dto\Query;
use Edde\Repository\AbstractRepository;
use Throwable;

/**
 * @Injectable(lazy=true)
 */
class LogRepository extends AbstractRepository {
	use LogTagRepositoryTrait;

	public function __construct() {
		parent::__construct(['microtime' => false]);
	}

	/**
	 * @param CreateDto $createDto
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 * @throws Throwable
	 */
	public function create(CreateDto $createDto) {
		$log = $this->insert([
			'log'       => $createDto->log,
			'type'      => $createDto->type,
			'trace'     => $createDto->traceId,
			'reference' => $createDto->referenceId,
			'stack'     => $createDto->stack,
			'microtime' => microtime(true),
			'stamp'     => new DateTime(),
			'user_id'   => $createDto->userId,
			'context'   => json_encode($createDto->context),
		]);
		$this->logTagRepository->sync($log->id, $createDto->tags);
		return $log;
	}

	public function toQuery(Query $query): Select {
		$select = $this->select();

		/** @var $filter LogFilterDto */
		$filter = $query->filter;
		$filter->types && $select->where('type', 'in', $filter->types);
		$filter->userIds && $select->where('user_id', 'in', $filter->userIds);
		$filter->reference && $select->where(function (SelectBase $selectBase) use ($filter) {
			$selectBase->orWhere('trace', $filter->reference);
			$selectBase->orWhere('reference', $filter->reference);
		});
		$filter->stamp && $filter->stamp->from && $select->where('stamp', '>=', $filter->stamp->from);
		$filter->stamp && $filter->stamp->to && $select->where('stamp', '<=', $filter->stamp->to);
		$filter->tagIds && $select
			->rightJoin('z_log_tag as lt', 'lt.log_id', '=', 'z_log.id')
			->where('tag_id', 'in', $filter->tagIds);

		$this->toOrderBy($query->orderBy, $select);

		return $select;
	}
}