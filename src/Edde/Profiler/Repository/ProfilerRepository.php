<?php
declare(strict_types=1);

namespace Edde\Profiler\Repository;

use Edde\Database\Repository\AbstractRepository;
use Edde\Profiler\Schema\ProfilerSchema;

class ProfilerRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(ProfilerSchema::class);
		$this->orderBy = [
			'$.stamp' => 'desc',
		];
	}
}
