<?php
declare(strict_types=1);

namespace Edde\Bridge\User;

use Edde\User\AbstractCurrentUser;

/**
 * This is ignored (by class-loader) class for current user just as a placeholder to implement custom user as
 * one could depend on proprietary properties and other class stuff for convenience (instead of
 * quite dummy interfaces in here).
 */
class CurrentUser extends AbstractCurrentUser {
}
