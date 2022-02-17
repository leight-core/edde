<?php
declare(strict_types=1);

namespace Edde\Excel\Dto\Export;

use Edde\Dto\AbstractDto;
use Edde\Source\Dto\QueriesDto;

class ExcelExportDto extends AbstractDto {
	/** @var QueriesDto */
	public $queries;
	/** @var string */
	public $templateId;
}
