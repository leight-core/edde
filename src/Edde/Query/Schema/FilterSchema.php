<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface FilterSchema {
    function id(): ?string;

    function idIn($array = true): ?string;

    function fulltext(): ?string;
}
