<?php
declare(strict_types=1);

namespace Edde\Mapper;

interface IMapperService {
	public function getMapper(?string $class): IMapper;
}
