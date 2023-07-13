<?php
declare(strict_types=1);

namespace Edde\Auth\Schema;

/**
 * Simple login request schema.
 */
interface LoginSchema {
	const meta = [
		'export' => 'Login',
	];

	function login(): string;

	function password(): string;
}
