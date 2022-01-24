<?php
declare(strict_types=1);

namespace Edde\User\Repository;

use Edde\Bridge\User\Repository\UserRepository;

trait UserRepositoryTrait {
	/** @var UserRepository */
	protected $userRepository;

	/**
	 * @Inject
	 *
	 * @param UserRepository $userRepository
	 */
	public function setUserRepository(UserRepository $userRepository): void {
		$this->userRepository = $userRepository;
	}
}
