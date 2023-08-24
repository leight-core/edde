<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Query;

use Edde\Query\Schema\FilterSchema;

interface JobFilterSchema extends FilterSchema {
    const meta = [
        'import' => [
            'JobFilterSchema'       => '@pico/job',
            'type IJobFilterSchema' => '@pico/job',
            'type IJobFilter'       => '@pico/job',
        ],
    ];

    function status(): ?int;

    function statusIn($array = true): ?int;

    function service(): ?string;

    function userId(): ?string;

    function serviceIn($array = true): ?string;
}
