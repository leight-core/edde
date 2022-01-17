<?php
declare(strict_types=1);

namespace Edde\Session;

trait SessionMapperTrait {
	/** @var ISessionMapper */
	protected $sessionMapper;

	/**
	 * @Inject
	 *
	 * @param ISessionMapper $sessionMapper
	 */
	public function setSessionMapper(ISessionMapper $sessionMapper): void {
		$this->sessionMapper = $sessionMapper;
	}
}
