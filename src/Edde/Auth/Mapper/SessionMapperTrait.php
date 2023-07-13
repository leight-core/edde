<?php
declare(strict_types=1);

namespace Edde\Auth\Mapper;

trait SessionMapperTrait {
	/**
	 * @var ISessionMapper
	 */
	protected $sessionMapper;

	/**
	 * @Inject
	 *
	 * @param ISessionMapper $sessionMapper
	 *
	 * @return void
	 */
	public function setSessionMapper(ISessionMapper $sessionMapper): void {
		$this->sessionMapper = $sessionMapper;
	}
}
