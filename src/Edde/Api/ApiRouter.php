<?php
declare(strict_types=1);

namespace Edde\Api;

use Edde\Cache\CacheTrait;
use Edde\Config\ConfigServiceTrait;
use Edde\Http\AbstractHttpRouter;
use Edde\Http\HttpIndexTrait;
use Edde\Log\LoggerTrait;
use Edde\Profiler\ProfilerServiceTrait;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Throwable;

/**
 * This is the Root Router (as for the beginning) for the whole application. Everything accessible via an
 * Endpoint is treated as an API.
 *
 * Also the name of this namespace is nice, because A is the first letter of an alphabet. Sooo, it's simple to just
 * jump into the stuff the application puts out to the World.
 */
class ApiRouter extends AbstractHttpRouter {
	use LoggerTrait;
	use HttpIndexTrait;
	use ProfilerServiceTrait;
	use ConfigServiceTrait;
	use CacheTrait;

	/**
	 * Option to specify endpoints directly bound into the root; this should be avoided.
	 */
	const CONFIG_ENDPOINTS = 'router.endpoints';
	/**
	 * Option to specify router groups (collection of endpoints and other router groups).
	 */
	const CONFIG_GROUPS = 'router.groups';
	/**
	 * Option to specify (optional) endpoint for handling all "the other" requests (for example static resources).
	 */
	const CONFIG_STATIC = 'router.static';

	public function register(App $app) {
		$this->profilerService->profile(static::class, function () use ($app) {
			try {
				$errorMiddleware = $app->addErrorMiddleware(false, true, true, $this->logger);
				$errorHandler = $errorMiddleware->getDefaultErrorHandler();
				$errorHandler
					->registerErrorRenderer('text/html', [
						$this,
						'handleException',
					]);
				$errorHandler
					->registerErrorRenderer('text/plain', [
						$this,
						'handleException',
					]);
				foreach ($this->httpIndex->endpoints(function () use ($app) {
					$this->endpoints(
						$app,
						$this->configService->system(self::CONFIG_ENDPOINTS, []),
						$this->configService->system(self::CONFIG_GROUPS, [])
					);
				}) as $endpoint) {
					$app->any($endpoint->link, $endpoint->class->fqdn);
				}
				($static = $this->configService->system(self::CONFIG_STATIC, false)) && $app->get('{path:.*}', $static);
			} catch (Throwable $e) {
				$this->cache->clear();
				throw $e;
			}
		});
	}

	public function handleException(Throwable $throwable): string {
		try {
			throw $throwable;
		} catch (HttpNotFoundException $exception) {
			return '4o4';
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			return '500';
		}
	}
}
