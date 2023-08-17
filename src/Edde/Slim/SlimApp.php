<?php
declare(strict_types=1);

namespace Edde\Slim;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\SQLLogger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Edde\Api\ApiRouter;
use Edde\Auth\Mapper\ISessionMapper;
use Edde\Bootstrap\IBootstrap;
use Edde\Cache\Cache;
use Edde\Cache\ICache;
use Edde\Cache\Impl\DatabaseCache;
use Edde\Dto\IDtoService;
use Edde\Dto\ISmartService;
use Edde\Dto\SmartService;
use Edde\EddeException;
use Edde\Excel\ExcelExportService;
use Edde\Excel\ExcelImportService;
use Edde\Excel\ExcelService;
use Edde\Excel\IExcelExportService;
use Edde\Excel\IExcelImportService;
use Edde\Excel\IExcelService;
use Edde\File\FileService;
use Edde\File\IFileService;
use Edde\Http\HttpIndex;
use Edde\Http\IHttpIndex;
use Edde\Http\IHttpRouter;
use Edde\Image\IImageService;
use Edde\Image\ImageService;
use Edde\Job\Command\JobExecutorCommand;
use Edde\Job\Executor\CliJobExecutor;
use Edde\Job\Executor\IJobExecutor;
use Edde\Job\Service\IJobLockService;
use Edde\Job\Service\IJobService;
use Edde\Job\Service\JobLockService;
use Edde\Job\Service\JobService;
use Edde\Log\DatabaseLogger;
use Edde\Mapper\IMapperService;
use Edde\Mapper\MapperService;
use Edde\Password\IPasswordService;
use Edde\Password\PasswordService;
use Edde\Php\IPhpBinaryService;
use Edde\Php\MemoryUsageMiddleware;
use Edde\Php\PhpBinaryService;
use Edde\Profiler\ProfilerMiddleware;
use Edde\Reflection\ReflectionDtoService;
use Edde\Rest\EndpointInfo;
use Edde\Rest\IEndpointInfo;
use Edde\Rpc\Service\IRpcHandlerIndex;
use Edde\Rpc\Service\RpcHandlerIndex;
use Edde\Schema\ISchemaLoader;
use Edde\Schema\ISchemaManager;
use Edde\Schema\ReflectionSchemaLoader;
use Edde\Schema\SchemaManager;
use Edde\Sdk\SdkCommand;
use Edde\Session\ISessionResolver;
use Edde\Session\SessionMiddleware;
use Edde\Session\SessionResolver;
use Edde\Source\ISourceService;
use Edde\Source\SourceService;
use Edde\Storage\StorageConfig;
use Edde\User\Mapper\IUserMapper;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use Phinx\Config\ConfigInterface;
use Phinx\Migration\Manager;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Slim\App;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
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
	/** @var SlimApp */
	static public $instance;

	public function __construct(App $app) {
		$this->app = $app;
	}

	public function add($middleware): SlimApp {
		$this->app->add($middleware);
		return $this;
	}

	public function injectOn($instance) {
		return $this->app->getContainer()->injectOn($instance);
	}

	public function dynamicBasePath(string $lookup = 'blackfox'): SlimApp {
		/**
		 * Guess base path to keep things working when moved between strange environments.
		 */
		if ($match = (Strings::match($_SERVER['REQUEST_URI'] ?? '', '~^(?<base>.*?/' . $lookup . ').*$~')['base']) ?? null) {
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
			'doctrine.dev'                => false,
			SessionInterface::class       => function (ContainerInterface $container) {
				return $container->get(Session::class);
			},
			Session::class                => function (ContainerInterface $container) {
				return $container->get(ISessionResolver::class)->setup();
			},
			ISessionResolver::class       => function (ContainerInterface $container) {
				return $container->get(SessionResolver::class);
			},
			IHttpRouter::class            => function (ContainerInterface $container) {
				return $container->get(ApiRouter::class);
			},
			IImageService::class          => function (ContainerInterface $container) {
				return $container->get(ImageService::class);
			},
			IJobExecutor::class           => function (ContainerInterface $container) {
				return $container->get(CliJobExecutor::class);
			},
			IDtoService::class            => function (ContainerInterface $container) {
				return $container->get(ReflectionDtoService::class);
			},
			IHttpIndex::class             => function (ContainerInterface $container) {
				return $container->get(HttpIndex::class);
			},
			IPhpBinaryService::class      => function (ContainerInterface $container) {
				return $container->get(PhpBinaryService::class);
			},
			IEndpointInfo::class          => function (ContainerInterface $container) {
				return $container->get(EndpointInfo::class);
			},
			IExcelService::class          => function (ContainerInterface $container) {
				return $container->get(ExcelService::class);
			},
			IExcelImportService::class    => function (ContainerInterface $container) {
				return $container->get(ExcelImportService::class);
			},
			IExcelExportService::class    => function (ContainerInterface $container) {
				return $container->get(ExcelExportService::class);
			},
			LoggerInterface::class        => function (ContainerInterface $container) {
				return $container->get(DatabaseLogger::class);
			},
			IPasswordService::class       => function (ContainerInterface $container) {
				return $container->get(PasswordService::class);
			},
			IFileService::class           => function (Container $container) {
				return $container->make(FileService::class, ['root' => $container->get(FileService::CONFIG_ROOT)]);
			},
			ISourceService::class         => function (ContainerInterface $container) {
				return $container->get(SourceService::class);
			},
			IMapperService::class  => function (ContainerInterface $container) {
				return $container->get(MapperService::class);
			},
			StorageConfig::class          => function (ContainerInterface $container) {
				return new StorageConfig($container->get(StorageConfig::CONFIG_STORAGE));
			},
			Application::class            => function (ContainerInterface $container) {
				$application = new Application($container->get(self::CONFIG_APP_NAME));
				foreach ($container->get(self::CONFIG_CLI) as $cli) {
					$application->add($container->get($cli));
				}
				$application->add($container->get(JobExecutorCommand::class));
				$application->add($container->get(SdkCommand::class));
				return $application;
			},
			IUserMapper::class            => function () {
				throw new EddeException(sprintf('[%s] is not implemented or registered in the container; please provide implementation of [%s].', IUserMapper::class, IUserMapper::class));
			},
			ISessionMapper::class         => function () {
				throw new EddeException(sprintf('[%s] is not implemented or registered in the container; please provide implementation of [%s].', ISessionMapper::class, ISessionMapper::class));
			},
			ICache::class                 => function (ContainerInterface $container) {
				return $container->get(Cache::class);
			},
			CacheInterface::class         => function (ContainerInterface $container) {
				return $container->get(DatabaseCache::class);
			},
			SlimApp::CONFIG_CLI           => [],
			ISmartService::class          => function (ContainerInterface $container) {
				return $container->get(SmartService::class);
			},
			ISchemaLoader::class          => function (ContainerInterface $container) {
				return $container->get(ReflectionSchemaLoader::class);
			},
			ISchemaManager::class         => function (ContainerInterface $container) {
				return $container->get(SchemaManager::class);
			},
			IRpcHandlerIndex::class       => function (ContainerInterface $container) {
				return $container->get(RpcHandlerIndex::class);
			},
			IJobLockService::class => function (ContainerInterface $container) {
				return $container->get(JobLockService::class);
			},
			IJobService::class     => function (ContainerInterface $container) {
				return $container->get(JobService::class);
			},
			Manager::class                => function (ContainerInterface $container) {
				$manager = new Manager($container->get(ConfigInterface::class), new ArrayInput([]), new StreamOutput(fopen('php://output', 'w')));
				$manager->setContainer($container);
				return $manager;
			},
			EntityManagerInterface::class => function (ContainerInterface $container) {
				/** @var $storageConfig StorageConfig */
				$storageConfig = $container->get(StorageConfig::class);
				$config = ORMSetup::createAnnotationMetadataConfiguration(
					[$container->get('source.root')],
					$isDev = $container->get('doctrine.dev') ?? false,
				);
				if (!$isDev) {
					$config->setQueryCache(new PhpFilesAdapter('doctrine.query', 3600));
					$config->setResultCache(new PhpFilesAdapter('doctrine.result', 3600));
					$config->setMetadataCache(new PhpFilesAdapter('doctrine.metadata', 3600));
				}
				$isDev && $config->setSQLLogger(new class implements SQLLogger {
					public function startQuery($sql, ?array $params = null, ?array $types = null) {
					}

					public function stopQuery() {
					}
				});
				$connection = DriverManager::getConnection(array_merge(
					['doctrine.driver' => $driver] = $storageConfig->getConfig(),
					[
						'driver'  => $driver,
						'charset' => 'UTF8',
					]
				), $config);
				return new EntityManager($connection, $config);
			},
		]);
		$containerBuilder->addDefinitions(...$definitions);
		$app = Bridge::create($container = $containerBuilder->build());

		$app->add(SessionMiddleware::class);
		$app->add(MemoryUsageMiddleware::class);
		$app->add(ProfilerMiddleware::class);
		$app->add(CorsMiddleware::class);

		$app->addBodyParsingMiddleware()
			->registerBodyParser('application/json', [
				Json::class,
				'decode',
			]);

		$container->has(IBootstrap::class) && $container->get(IBootstrap::class)->bootstrap();

		return self::$instance = new self($app);
	}
}
