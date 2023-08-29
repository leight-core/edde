<?php
declare(strict_types=1);

namespace Edde\Upgrade\Schema;

use Edde\Query\Schema\CursorSchema;

interface UpgradeQuerySchema {
    function where($load = true): ?UpgradeFilterSchema;

    function filter($load = true): ?UpgradeFilterSchema;

    function orderBy($load = true): ?UpgradeOrderBySchema;

    function cursor($load = true): ?CursorSchema;
}
