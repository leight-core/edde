<?php

use Edde\File\FileService;
use Edde\Storage\StorageConfig;

$phinx = (require __DIR__ . '/phinx.config.php');
$database = $phinx['environments'][$phinx['environments']['default_environment']];

return [
	StorageConfig::CONFIG_STORAGE => [
		'driver'   => 'pdo',
		'dsn'      => sprintf('mysql:host=%s;dbname=%s;charset=utf8', $database['host'], $database['name']),
		'user'     => $database['user'],
		'password' => $database['pass'],
	],
	FileService::CONFIG_ROOT      => __DIR__ . '/../.data',
];
