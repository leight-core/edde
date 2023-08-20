<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

use Edde\Database\Repository\AbstractRepository;
use Edde\Dto\SmartDto;

class BulkItemRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct();
		$this->orderBy = [
			'$.created' => 'desc',
		];
	}

	protected function applyWhere(SmartDto $filter, SmartDto $query, QueryBuilder $builder): void {
		parent::applyWhere($filter, $query, $builder);
		$filter->knownWithValue('bulkId') && $this->matchOf($builder, '$.bulkId', $filter->getValue('bulkId'));
	}
}
