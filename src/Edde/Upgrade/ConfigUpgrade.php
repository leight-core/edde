<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class ConfigUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_config', ['comment' => 'Runtime application configuration moved into the table instead of config files somewhere.'])
			->addStringColumn('key', 256, [
				'comment' => 'Config key (unique).',
				'unique'  => true,
			])
			->addTextColumn('value', [
				'comment' => 'Configuration value; could be basically everything',
				'null'    => true,
			])
			->save();
	}
}
