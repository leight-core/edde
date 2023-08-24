<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Query;

interface JobOrderBySchema {
    const meta = [
        'orderBy' => [
            'created',
            'status',
        ],
        'import'  => [
            'JobOrderBySchema'       => '@pico/job',
            'type IJobOrderBySchema' => '@pico/job',
            'type IJobOrderBy'       => '@pico/job',
        ],
    ];
}
