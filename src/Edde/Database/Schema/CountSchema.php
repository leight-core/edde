<?php
declare(strict_types=1);

namespace Edde\Database\Schema;

interface CountSchema {
    const meta = [
        'import' => [
            'CountSchema'       => '@pico/query',
            'type ICount'       => '@pico/query',
            'type ICountSchema' => '@pico/query',
        ],
    ];

    function total(): int;

    function count(): int;
}
