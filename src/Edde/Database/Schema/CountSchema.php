<?php
declare(strict_types=1);

namespace Edde\Database\Schema;

interface CountSchema {
    const meta = [
        'import' => [
            'CountSchema'       => '@pico/source',
            'type ICount'       => '@pico/source',
            'type ICountSchema' => '@pico/source',
        ],
    ];

    function count(): int;
}
