<?php
declare(strict_types=1);

namespace Edde\Bridge\User;

use Edde\User\AbstractUser;

/**
 * UserDto is ignored by class-loader prepared to be implemented on the application side.
 *
 * This is "public" representation of an user; I (as CurrentUser) can see others (as UserDto) thus data
 * of them must be separated.
 *
 * This class shall not provide more data than necessary as the others could see them eventually.
 */
class UserDto extends AbstractUser {
}
