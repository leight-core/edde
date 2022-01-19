<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class ProfilerUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_profiler', ['comment' => 'Application profiler table.'])
			->addColumn('stamp', 'double', ['comment' => 'Microtime (timestamp) of the record'])
			->addStringColumn('name', 1024, ['comment' => 'Profiled entry name'])
			->addColumn('runtime', 'double', ['comment' => 'Profiled entry runtime'])
			->save();
	}
}
