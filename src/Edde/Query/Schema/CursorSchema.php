<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface CursorSchema {
	const meta = [
		'import' => [
			'type ICursorSchema' => '@leight/query',
			'type ICursor'       => '@leight/query',
			'CursorSchema'       => '@leight/query',
		],
	];

	function page(): int;

	function size(): int;
}
