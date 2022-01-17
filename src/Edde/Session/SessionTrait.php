<?php
declare(strict_types=1);

namespace Edde\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait SessionTrait {
	/** @var SessionInterface */
	protected $session;

	/**
	 * @Inject
	 *
	 * @param SessionInterface $session
	 */
	public function setSession(SessionInterface $session): void {
		$this->session = $session;
	}
}
