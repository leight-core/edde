<?php
declare(strict_types=1);

namespace Edde\Math;

use function password_hash;

class PasswordService extends AbstractPasswordService {
	public function isMatch(string $password, string $hash): bool {
		return password_verify($password, $hash);
	}

	public function hash(string $password): bool {
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
	}
}
