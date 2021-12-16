<?php
declare(strict_types=1);

namespace Edde\Slim;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use DI\ContainerBuilder;
use Edde\Api\ApiRouter;
use Edde\Cache\DatabaseCache;
use Edde\Dto\IDtoService;
use Edde\File\FileService;
use Edde\File\IFileService;
use Edde\Http\HttpIndex;
use Edde\Http\IHttpIndex;
use Edde\Http\IHttpRouter;
use Edde\Job\CliJobExecutor;
use Edde\Job\Command\JobExecutorCommand;
use Edde\Job\IJobExecutor;
use Edde\Log\DatabaseLogger;
use Edde\Php\IPhpBinaryService;
use Edde\Php\MemoryUsageMiddleware;
use Edde\Php\PhpBinaryService;
use Edde\Profiler\ProfilerMiddleware;
use Edde\Reflection\ReflectionDtoService;
use Edde\Rest\EndpointInfo;
use Edde\Rest\IEndpointInfo;
use Edde\Session\SessionMiddleware;
use Edde\Storage\StorageConfig;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use Phinx\Config\ConfigInterface;
use Phinx\Migration\Manager;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Slim\App;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use function fopen;

class SlimApp {
	const CONFIG_APP_NAME = 'app.name';
	const CONFIG_CLI = 'app.cli';

	/** @var App */
	protected $app;

	public function __construct(App $app) {
		$this->app = $app;
	}

	public function add($middleware): SlimApp {
		$this->app->add($middleware);
		return $this;
	}

	public function injectOn($instance): SlimApp {
		$this->app->getContainer()->injectOn($instance);
		return $this;
	}

	public function dynamicBasePath(string $lookup = 'blackfox'): SlimApp {
		/**
		 * Guess base path to keep things working when moved between strange environments.
		 */
		if ($match = Strings::match($_SERVER['REQUEST_URI'] ?? '', '~^(?<base>.*?/' . $lookup . ').*$~')['base']) {
			$this->app->setBasePath($match);
		}
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

	public function cli() {
		return $this->app->getContainer()->get(Application::class)->run();
	}

	static public function create(...$definitions): SlimApp {
		$containerBuilder = new ContainerBuilder();
		$containerBuilder->useAnnotations(true);
		$containerBuilder->addDefinitions([
			SessionInterface::class  => function (ContainerInterface $container) {
				return $container->get(Session::class);
			},
			IHttpRouter::class       => function (ContainerInterface $container) {
				return $container->get(ApiRouter::class);
			},
			IJobExecutor::class      => function (ContainerInterface $container) {
				return $container->get(CliJobExecutor::class);
			},
			IDtoService::class       => function (ContainerInterface $container) {
				return $container->get(ReflectionDtoService::class);
			},
			IHttpIndex::class        => function (ContainerInterface $container) {
				return $container->get(HttpIndex::class);
			},
			IPhpBinaryService::class => function (ContainerInterface $container) {
				return $container->get(PhpBinaryService::class);
			},
			IEndpointInfo::class     => function (ContainerInterface $container) {
				return $container->get(EndpointInfo::class);
			},
			CacheInterface::class    => function (ContainerInterface $container) {
				return $container->get(DatabaseCache::class);
			},
			LoggerInterface::class   => function (ContainerInterface $container) {
				return $container->get(DatabaseLogger::class);
			},
			IFileService::class      => function (Container $container) {
				return $container->make(FileService::class, ['root' => $container->get(FileService::CONFIG_ROOT)]);
			},
			StorageConfig::class     => function (ContainerInterface $container) {
				return new StorageConfig($container->get(StorageConfig::CONFIG_STORAGE));
			},
			Application::class       => function (ContainerInterface $container) {
				$application = new Application($container->get(self::CONFIG_APP_NAME));
				foreach ($container->get(self::CONFIG_CLI) as $cli) {
					$application->add($container->get($cli));
				}
				$application->add($container->get(JobExecutorCommand::class));
				return $application;
			},
			Manager::class           => function (ContainerInterface $container) {
				$manager = new Manager($container->get(ConfigInterface::class), new ArrayInput([]), new StreamOutput(fopen('php://output', 'w')));
				$manager->setContainer($container);
				return $manager;
			},
		]);
		$containerBuilder->addDefinitions(...$definitions);
		$app = Bridge::create($containerBuilder->build());

		$app->add(SessionMiddleware::class);
		$app->add(MemoryUsageMiddleware::class);
		$app->add(ProfilerMiddleware::class);

		$app->addBodyParsingMiddleware()
			->registerBodyParser('application/json', [
				Json::class,
				'decode',
			]);

		return new self($app);
	}
}
