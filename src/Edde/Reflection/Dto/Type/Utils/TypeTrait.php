<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

/**
 * Abstract type without any specification; this should not be used at all as it means Reflection does not understand the given
 * type.
 */
trait TypeTrait {
	/**
	 * @var string
	 * @description type of the object; internally used by the Reflection
	 * @internal
	 */
	public $__type;
	/**
	 * @var bool
	 * @description is the property internal (marked with "internal") annotation?
	 */
	public $isInternal;
	/**
	 * @var bool
	 * @description is the type marked as required (non nullable)?
	 */
	public $isRequired;
	/**
	 * @var bool
	 * @description can by this type omitted at all (for example class property)
	 */
	public $isOptional;
	/**
	 * @var bool
	 * @description is a type an array of the type?
	 */
	public $isArray;
}
