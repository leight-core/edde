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

	protected function applyWhere(string $alias, SmartDto $query, QueryBuilder $queryBuilder): void {
		if (!($filter = $query->getSmartDto('filter'))) {
			return;
		}
		parent::applyWhere($alias, $query, $queryBuilder);
		$filter->knownWithValue('bulkId') && $this->matchOf($queryBuilder, $alias, 'bulk_id', $filter->getValue('bulkId'));
	}
}
