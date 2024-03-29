<?php
declare(strict_types=1);

namespace Edde\Bulk\Schema\Bulk;

use Edde\Database\Schema\UuidSchema;
use Edde\Dto\Mapper\ImportMapper;
use Edde\Dto\Mapper\ITypeMapper;
use Edde\Utils\Mapper\IntBoolMapper;

interface BulkSchema extends UuidSchema {
    const meta = [
        'import'           => [
            'type IBulk'       => '@pico/bulk',
            'type IBulkSchema' => '@pico/bulk',
            'BulkSchema'       => '@pico/bulk',
        ],
        ImportMapper::META => [
            'user_id' => ImportMapper::CONVERT_CAMEL,
        ],
    ];

    function name(): string;

    function status(): int;

    function commit(
        $output = IntBoolMapper::class
    ): bool;

    function created(
        $type = ITypeMapper::TYPE_ISO_DATETIME
    ): string;

    function userId(): string;
}
