<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Mapper\IMapper;

interface ITypeMapper extends IMapper {
	public const TYPE_JSON = 'json';
	public const TYPE_BOOLINT = 'boolint';
	public const TYPE_ISO_DATETIME = 'iso-datetime';
}
