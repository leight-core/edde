<?php
declare(strict_types=1);

namespace Edde\User;

trait UserRepositoryTrait {
	/** @var IUserRepository */
	protected $userRepository;

	/**
	 * @Inject
	 *
	 * @param IUserRepository $userRepository
	 */
	public function setUserRepository(IUserRepository $userRepository): void {
		$this->userRepository = $userRepository;
	}
}
