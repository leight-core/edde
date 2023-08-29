<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface QuerySchema {
    function cursor($load = true): ?CursorSchema;

    function where($type = FilterSchema::class, $load = true);

    function filter($type = FilterSchema::class, $load = true);
}
