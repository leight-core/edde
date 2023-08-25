<?php
declare(strict_types=1);

namespace Edde\Query\Schema;

interface FilterSchema {
    function id(): ?string;

    function idIn(): ?string;

    function fulltext(): ?string;
}
