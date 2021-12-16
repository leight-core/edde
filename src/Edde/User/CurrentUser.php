<?php
declare(strict_types=1);

namespace Edde\User;

use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\User\Exception\UserNotSelectedException;
use Edde\User\Mapper\CurrentUserMapperTrait;
use Edde\User\Repository\UserRepositoryTrait;
use function array_merge;

/**
 * @Injectable(lazy=true)
 */
class CurrentUser {
	use UserRepositoryTrait;
	use CurrentUserMapperTrait;

	/** @var CurrentUser|null */
	protected $user;

	/**
	 * Selects current user by the given userId.
	 *
	 * @param mixed $userId mixed is intentional as it accepts "strange" ids from int, string as int and strings; it goes down to Repository which could handle it properly
	 *
	 * @return CurrentUser|null
	 *
	 * @throws ItemException
	 * @throws SkipException
	 */
	public function select($userId): ?CurrentUser {
		$this->user = null;
		if ($userId) {
			$this->user = $this->currentUserMapper->item($this->userRepository->findByLogin($userId));
		}
		return $this->user;
	}

	public function selectBy(string $login): ?CurrentUser {
		if ($user = $this->userRepository->findByLogin($login)) {
			return $this->select($user->id);
		}
		throw new UserNotSelectedException(sprintf("Cannot select user [%s]; probably does not exist.", $login));
	}

	/**
	 * Optionally return an user id if it's available.
	 *
	 * @return string|null
	 */
	public function optionalId(): ?string {
		return $this->user ? $this->user->id : null;
	}

	/**
	 * When an user id is required, use this method.
	 *
	 * @return string
	 *
	 * @throws UserNotSelectedException
	 */
	public function requiredId(): string {
		if (!($id = $this->optionalId())) {
			throw new UserNotSelectedException('Requested an user ID, but no user has been selected.');
		}
		return $id;
	}

	public function isSelected(): bool {
		return !!$this->user;
	}

	public function requireUser(): CurrentUser {
		if (!$this->user) {
			throw new UserNotSelectedException('Requested an user, but no user has been selected.');
		}
		return $this->user;
	}

	public function publicUser(array $extra = []): CurrentUser {
		return CurrentUser::create(array_merge([
			'id'    => null,
			'name'  => 'public',
			'login' => 'public',
			'site'  => 'public',
			'roles' => [],
		], $extra));
	}
}
