<?php
declare(strict_types=1);

namespace Edde\User;

/**
 * Current user in the request, regardless of session; for example CLI user could be
 * set by a different way.
 */
trait CurrentUserTrait {
	/** @var CurrentUser */
	protected $currentUser;

	/**
	 * @Inject
	 *
	 * @param CurrentUser $currentUser
	 */
	public function setCurrentUser(CurrentUser $currentUser): void {
		$this->currentUser = $currentUser;
	}
}
