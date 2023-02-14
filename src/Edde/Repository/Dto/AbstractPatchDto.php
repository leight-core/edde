<?php
declare(strict_types=1);

namespace Edde\Repository\Dto;

use Edde\Dto\AbstractDto;

class AbstractPatchDto extends AbstractDto {
	public $id;
	/**
	 * Select fields for patching; because fuckin' PHP does not know NULL, client has to
	 * do the job instead of PHP; when NULL is provided, it's treated as a value; if `undefined` is
	 * provided, it should not be present in the fields at all.
	 *
	 * If this field is not provided at all, it assumes al values should be patched with the provided
	 * values.
	 *
	 * @var string[]|null
	 */
	public $fields;
}
