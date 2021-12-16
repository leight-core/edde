<?php
declare(strict_types=1);

namespace Edde\User\Mapper;

trait CurrentUserMapperTrait {
	/** @var ICurrentUserMapper */
	protected $currentUserMapper;

	/**
	 * @Inject
	 *
	 * @param ICurrentUserMapper $currentUserMapper
	 */
	public function setCurrentUserMapper(ICurrentUserMapper $currentUserMapper): void {
		$this->currentUserMapper = $currentUserMapper;
	}
}
