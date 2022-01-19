<?php
declare(strict_types=1);

namespace Edde\Password;

use Edde\Password\Exception\PasswordException;

abstract class AbstractPasswordService implements IPasswordService {
	public function check(string $password, string $hash): void {
		if (!$this->isMatch($password, $hash)) {
			unset($password, $hash);
			throw new PasswordException('Wrong password.');
		}
	}
}
