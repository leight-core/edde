<?php
declare(strict_types=1);

namespace Edde\Repository;

use ClanCats\Hydrahon\Query\Sql\Delete;
use ClanCats\Hydrahon\Query\Sql\Select;
use Dibi\Result;
use Edde\Mapper\IMapper;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;

/**
 * @template TItem
 */
interface IRepository {
	const ORDER_ASC = true;
	const ORDER_DESC = false;

	/**
	 * Return total count of items in this repository; it's used mainly for paging support.
	 *
	 * @param Query $query
	 *
	 * @return int
	 */
	public function total(Query $query): int;

	/**
	 * Return page from this repository of null if no data is available.
	 *
	 * @param Query $query
	 *
	 * @return Result|null
	 */
	public function query(Query $query): iterable;

	/**
	 * @param Query|null $query
	 *
	 * @return Result
	 */
	public function execute(?Query $query = null): iterable;

	public function toQuery(Query $query): Select;

	public function delete(string $id);

	public function deleteWhere(): Delete;

	/**
	 * @param Query   $query
	 * @param IMapper $mapper
	 *
	 * @return QueryResult<TItem>
	 */
	public function toResult(Query $query, IMapper $mapper): QueryResult;
}
