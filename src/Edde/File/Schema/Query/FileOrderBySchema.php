<?php
declare(strict_types=1);

namespace Edde\File\Schema\Query;

interface FileOrderBySchema {
	const meta = [
		'orderBy' => [
			'name',
			'path',
		],
	];

	function name(): ?string;

	function path(): ?string;
}
