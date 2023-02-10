<?php
declare(strict_types=1);

namespace Edde\Repository;

use ClanCats\Hydrahon\Query\Sql\Delete;
use ClanCats\Hydrahon\Query\Sql\Select;
use ClanCats\Hydrahon\Query\Sql\SelectBase;
use ClanCats\Hydrahon\Query\Sql\Table;
use Dibi\Result;
use Edde\Mapper\IMapper;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;

/**
 * @template TItem
 */
interface IRepository extends IMapper {
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

	/**
	 * @return Table
	 */
	public function table(): Table;

	public function toQuery(Query $query): Select;

	public function delete(string $id);

	public function deleteBy(Query $query): void;

	public function deleteWhere(): Delete;

	/**
	 * @param Query   $query
	 * @param IMapper $mapper
	 *
	 * @return QueryResult<TItem>
	 */
	public function toResult(Query $query, IMapper $mapper): QueryResult;

	/**
	 * Executed when changes are detected on an update/create/update.
	 *
	 * @param mixed  $original original row (null when created)
	 * @param mixed  $changed  new values (null when deleted)
	 * @param string $type     type of a diff (create/update/delete)
	 */
	public function diffOf($original, $changed, string $type): void;

	/**
	 * If repository should support more complex queries with access to main select, this method could be used.
	 * You can add joins and other parameters based on the filter.
	 *
	 * This method should call applyWhere, keep that in mind.
	 */
	public function applyFilter($filter, Select $select): void;

	/**
	 * General way how to apply a filter on select/update/delete.
	 */
	public function applyWhere($filter, SelectBase $selectBase): void;
}
