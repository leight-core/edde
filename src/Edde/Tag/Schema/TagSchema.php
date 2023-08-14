<?php
declare(strict_types=1);

namespace Edde\Tag\Schema;

interface TagSchema {
	const meta = [
		'import' => [
			'type ITag'       => '@leight/utils',
			'type ITagSchema' => '@leight/utils',
			'TagSchema'       => '@leight/utils',
		],
	];

	function id(): string;

	function code(): string;

	function tag(): string;

	function group(): string;

	function sort(): ?int;
}
