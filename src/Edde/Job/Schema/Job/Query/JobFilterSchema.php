<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Query;

use Edde\Query\Schema\FilterSchema;

interface JobFilterSchema extends FilterSchema {
    function status(): ?int;

    function statusIn($array = true): ?int;

    function service(): ?string;

    function userId(): ?string;

    function serviceIn($array = true): ?string;
}
