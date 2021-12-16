<?php
declare(strict_types=1);

namespace Edde\User\Mapper;

trait UserMapperTrait {
	/** @var IUserMapper */
	protected $userMapper;

	/**
	 * @Inject
	 *
	 * @param IUserMapper $userMapper
	 */
	public function setUserMapper(IUserMapper $userMapper): void {
		$this->userMapper = $userMapper;
	}
}
