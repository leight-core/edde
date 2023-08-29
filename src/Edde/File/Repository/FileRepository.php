<?php
declare(strict_types=1);

namespace Edde\File\Repository;

use Edde\Database\Repository\AbstractRepository;
use Edde\File\Schema\FileSchema;

class FileRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(FileSchema::class);
		$this->orderBy = [
			'$.created' => 'desc',
			'$.path'    => 'asc',
			'$.name'    => 'asc',
		];
	}
}
