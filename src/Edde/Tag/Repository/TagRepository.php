<?php
declare(strict_types=1);

namespace Edde\Tag\Repository;

use Edde\Database\Repository\AbstractRepository;
use Edde\Tag\Schema\TagSchema;

class TagRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(TagSchema::class);
		$this->orderBy = [
			'$.sort' => 'asc',
		];
	}
}
