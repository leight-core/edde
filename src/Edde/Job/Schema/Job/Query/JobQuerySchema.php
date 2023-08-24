<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Query;

use Edde\Query\Schema\CursorSchema;

interface JobQuerySchema {
    const meta = [
        'import' => [
            'JobQuerySchema'       => '@pico/job',
            'type IJobQuerySchema' => '@pico/job',
            'type IJobQuery'       => '@pico/job',
        ],
    ];

    function filter($load = true): ?JobFilterSchema;

    function orderBy($load = true): ?JobOrderBySchema;

    function cursor($load = true): ?CursorSchema;
}
