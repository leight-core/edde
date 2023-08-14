<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface WithIdentitySchema {
	const meta = [
		'import' => [
			'type IWithIdentity'       => '@leight/query',
			'type IWithIdentitySchema' => '@leight/query',
			'WithIdentitySchema'       => '@leight/query',
		],
	];

	function id(): string;
}
