<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

use Edde\Dto\Mapper\ITypeMapper;

interface JobCreateSchema {
    function service(): string;

    function reference(): ?string;

    function started(
        $type = ITypeMapper::TYPE_ISO_DATETIME
    );

    function request(
        $type = ITypeMapper::TYPE_JSON
    );

    function requestSchema(): ?string;
}
