<?php
declare(strict_types=1);

namespace Edde\File\Schema;

use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ITypeMapper;

interface FileSchema extends UuidSchema {
	function path(): string;

	function name(): string;

	function mime(): string;

	function size(): int;

	function native(): string;

	function created(
		$type = ITypeMapper::TYPE_ISO_DATETIME
	): string;

	function updated(
		$type = ITypeMapper::TYPE_ISO_DATETIME
	): ?string;

	function ttl(): float;

	function userId(): ?string;
}
