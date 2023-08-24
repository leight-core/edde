<?php
declare(strict_types=1);

namespace Edde\Job\Schema\JobLog\Query;

use Edde\Query\Schema\FilterSchema;

interface JobLogFilter extends FilterSchema {
    function jobId(): ?string;
}
