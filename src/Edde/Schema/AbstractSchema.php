<?php
declare(strict_types=1);

namespace Edde\Schema;

/**
 * Optional schema root with some default schema hints.
 */
abstract class AbstractSchema {
	/**
	 * User-defined metadata (could be anything)
	 */
	const meta = [];
	/**
	 * Marks this schema as partial (removing required flag on all properties).
	 */
	const partial = false;
	/**
	 * Marks selected properties with required flag; this wins over partial.
	 *
	 * "property-name => required"
	 */
	const required = [];
}
