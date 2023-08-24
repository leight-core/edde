<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Internal;

use Edde\Database\Schema\UuidGeneratorSchema;
use Edde\Dto\Mapper\ExportMapper;
use Edde\Dto\Mapper\ITypeMapper;
use Edde\Utils\Mapper\JsonInputMapper;

interface JobCreateSchema extends UuidGeneratorSchema {
    const meta = [
        ExportMapper::META => [
            'userId' => ExportMapper::CONVERT_SNAKE,
        ],
    ];

    function service(): string;

    function status(): int;

    function total(): int;

    function progress(): float;

    function successCount(): int;

    function errorCount(): int;

    function skipCount(): int;

    function started(
        $type = ITypeMapper::TYPE_ISO_DATETIME
    ): string;

    function request(
        $input = JsonInputMapper::class
    ): ?string;

    function requestSchema(): string;

    function commit(
        $type = ITypeMapper::TYPE_BOOLINT
    ): bool;

    function userId(): string;
}
