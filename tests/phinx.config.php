<?php

use Edde\Phinx\CommonMigration;

return [
	'paths'                => [
		'migrations' => '%%PHINX_CONFIG_DIR%%/upgrade',
	],
	'migration_base_class' => CommonMigration::class,
	'environments'         => [
		'default_migration_table' => 'migrations',
		'default_environment'     => 'edde',
		'edde'                    => [
			'adapter' => 'mysql',
			'host'    => 'edde-mysql',
			'name'    => 'edde',
			'user'    => 'root',
			'pass'    => '1234',
			'port'    => '3306',
			'charset' => 'utf8',
		],
	],
	'version_order'        => 'creation',
];
