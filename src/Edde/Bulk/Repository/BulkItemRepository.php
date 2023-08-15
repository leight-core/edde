<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

use Edde\Bulk\Entity\BulkItemEntity;
use Edde\Doctrine\AbstractRepository;

class BulkItemRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(BulkItemEntity::class);
		$this->orderBy = [
			'$.created' => 'desc',
		];
	}
}
