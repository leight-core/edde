<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

use Doctrine\ORM\QueryBuilder;
use Edde\Bulk\Entity\BulkItemEntity;
use Edde\Doctrine\AbstractRepository;
use Edde\Dto\SmartDto;

class BulkItemRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(BulkItemEntity::class);
		$this->orderBy = [
			'$.created' => 'desc',
		];
	}

	protected function applyWhere(string $alias, SmartDto $filter, SmartDto $query, QueryBuilder $queryBuilder): void {
		parent::applyWhere($alias, $filter, $query, $queryBuilder);
		$filter->knownWithValue('bulkId') && $this->matchOf($queryBuilder, $alias, 'bulkId', $filter->getValue('bulkId'));
	}
}
