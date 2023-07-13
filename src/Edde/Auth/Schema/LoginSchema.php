<?php
declare(strict_types=1);

namespace Edde\Auth\Schema;

/**
 * Simple login request schema.
 */
interface LoginSchema {
	const meta = [
		'module' => 'LoginSchema',
	];

	function login(): string;

	function password(): string;
}
