<?php
declare(strict_types=1);

namespace Edde\Auth\Schema;

/**
 * Session schema used for logged-in user. This should contain only
 * necessary data on the wire.
 */
interface SessionSchema {
	const meta = [
		'export' => 'Session',
	];

	/**
	 * User id
	 */
	function id(): string;

	/**
	 * Display name of the user
	 */
	function name(): string;

	/**
	 * Default site of the user
	 */
	function site(): ?string;

	function tokens($isArray = true): string;
}
