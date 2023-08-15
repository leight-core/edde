<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

use Edde\Bulk\Entity\BulkEntity;
use Edde\Doctrine\AbstractRepository;

class BulkRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(BulkEntity::class);
		$this->orderBy = [
			'$.created' => 'DESC',
		];
	}
}
