<?php
declare(strict_types=1);

namespace Edde\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface ISessionResolver {
	/**
	 * Configuration section for the session.
	 */
	const CONFIG_SESSION = 'session';

	public function setup(): SessionInterface;
}
