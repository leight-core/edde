<?php
declare(strict_types=1);

namespace Edde\Session;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Container\ContainerTrait;
use Edde\Log\LoggerTrait;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Repository\UserRepositoryTrait;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Throwable;
use const PHP_SAPI;

class SessionResolver implements ISessionResolver {
	use ContainerTrait;
	use CurrentUserServiceTrait;
	use UserRepositoryTrait;
	use LoggerTrait;

	/**
	 * @return SessionInterface
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function setup(): SessionInterface {
		$settings = $this->container->get(self::CONFIG_SESSION);
		if (PHP_SAPI === 'cli') {
			return new Session(new MockArraySessionStorage());
		}
		$session = new Session(new NativeSessionStorage($settings));
		/**
		 * I don't like way, how this is done, but for Session it's ensured this line runs,
		 * thus it's currently kind of workaround.
		 *
		 * If you will ever wondering, how the hell user is logged in, this magical piece of code
		 * does it: just selects current user based on an ID from the session.
		 */
		{
			try {
				/**
				 * Because the app runs in the *** environment where session stuff is heavily unreliable,
				 * this is just to keep the app (hopefully) alive.
				 */
				if (!$session->isStarted()) {
					$session->start();
				}
			} catch (Throwable $throwable) {
				$this->logger->error($throwable);
			}

			/**
			 * Try resolve an user from the $_SESSION directly as Symfony does some dark magick which prevents direct access
			 * to native $_SESSION.
			 *
			 * This user is the one logged from MarshConnect.
			 */
			try {
				/**
				 * At the first glance, use "default" user; if not available, use the one provided from
				 * MarshConnect session.
				 */
				$this->currentUserService->select($session->get('user'));
			} catch (Throwable $throwable) {
				/**
				 * Selecting an user should not kill the application itself. The "worst" thing could happen is an user seemingly logged out.
				 *
				 * This should prevent breaking when there is an upgrade on the user used here before actual upgrade could even run.
				 */
				$this->logger->error($throwable);
			}
		}
		return $session;
	}
}
