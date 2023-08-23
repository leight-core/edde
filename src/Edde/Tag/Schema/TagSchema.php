<?php
declare(strict_types=1);

namespace Edde\Tag\Schema;

interface TagSchema {
	const meta = [
		'import' => [
            'type ITag'       => '@pico/utils',
            'type ITagSchema' => '@pico/utils',
            'TagSchema'       => '@pico/utils',
		],
	];

	function id(): string;

	function code(): string;

	function tag(): string;

	function group(): string;

	function sort(): ?int;
}
