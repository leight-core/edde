<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

abstract class WithIdentitySchema {
	const meta = [
		'import' => [
			'WithIdentitySchema' => '@leight/query',
		],
	];

	abstract public function id(): string;
}
