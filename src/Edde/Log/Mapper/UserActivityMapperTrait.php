<?php
declare(strict_types=1);

namespace Edde\Log\Mapper;

trait UserActivityMapperTrait {
	/** @var UserActivityMapper */
	protected $userActivityMapper;

	/**
	 * @Inject
	 *
	 * @param UserActivityMapper $userActivityMapper
	 */
	public function setUserActivityMapper(UserActivityMapper $userActivityMapper): void {
		$this->userActivityMapper = $userActivityMapper;
	}
}
