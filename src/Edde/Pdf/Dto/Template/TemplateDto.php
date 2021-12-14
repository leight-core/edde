<?php
declare(strict_types=1);

namespace Edde\Pdf\Dto\Template;

use Edde\Dto\AbstractDto;

class TemplateDto extends AbstractDto {
	/**
	 * @var string
	 * @description source template ZIP file (with pdf source and yml definition)
	 */
	public $source;
	/**
	 * @var string|null
	 * @description target file; if not provided, temp file will be created
	 */
	public $target;
	/**
	 * @var mixed
	 * @description values being used to fill the template
	 */
	public $values = [];
}
