<?php
declare(strict_types=1);

namespace Edde\Slim;

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Edde\Http\IHttpRouter;
use Edde\Php\MemoryUsageMiddleware;
use Edde\Profiler\ProfilerMiddleware;
use Slim\App;

class SlimApp {
	/** @var App */
	protected $app;

	public function __construct(App $app) {
		$this->app = $app;
	}

	public function add($middleware): SlimApp {
		$this->app->add($middleware);
		return $this;
	}

	public function setBasePath(string $basePath): SlimApp {
		$this->app->setBasePath($basePath);
		return $this;
	}

	public function run(): void {
		/** @var $router IHttpRouter */
		$router = $this->app
			->getContainer()
			->get(IHttpRouter::class);
		$router->register($this->app);
		$this->app->run();
	}

	static public function create(...$definitions): SlimApp {
		$containerBuilder = new ContainerBuilder();
		$containerBuilder->useAnnotations(true);
		$containerBuilder->addDefinitions(...$definitions);
		$app = Bridge::create($container = $containerBuilder->build());

		$app->add(MemoryUsageMiddleware::class);
		$app->add(ProfilerMiddleware::class);

		$app->addBodyParsingMiddleware()
			->registerBodyParser('application/json', 'json_decode');

		return new self($app);
	}
}
