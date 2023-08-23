<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface CursorSchema {
	const meta = [
		'import' => [
            'type ICursorSchema' => '@pico/query',
            'type ICursor'       => '@pico/query',
            'CursorSchema'       => '@pico/query',
		],
	];

	function page(): int;

	function size(): int;
}
