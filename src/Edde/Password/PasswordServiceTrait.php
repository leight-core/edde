<?php
declare(strict_types=1);

namespace Edde\Password;

trait PasswordServiceTrait {
	/** @var IPasswordService */
	protected $passwordService;

	/**
	 * @Inject
	 *
	 * @param IPasswordService $passwordService
	 */
	public function setPasswordService(IPasswordService $passwordService): void {
		$this->passwordService = $passwordService;
	}
}
