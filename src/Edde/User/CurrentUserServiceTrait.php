<?php
declare(strict_types=1);

namespace Edde\User;

/**
 * Current user in the request, regardless of session; for example CLI user could be
 * set by a different way.
 */
trait CurrentUserServiceTrait {
	/** @var CurrentUserService */
	protected $currentUserService;

	/**
	 * @Inject
	 *
	 * @param CurrentUserService $currentUserService
	 */
	public function setCurrentUserService(CurrentUserService $currentUserService): void {
		$this->currentUserService = $currentUserService;
	}
}
