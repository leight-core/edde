<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLog\Query;

interface JobLogQuery {
    function filter($load = true): ?JobLogFilter;
}
