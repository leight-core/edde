<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface WithIdentitySchema {
	const meta = [
		'import' => [
            'type IWithIdentity'       => '@pico/query',
            'type IWithIdentitySchema' => '@pico/query',
            'WithIdentitySchema'       => '@pico/query',
		],
	];

	function id(): string;
}
