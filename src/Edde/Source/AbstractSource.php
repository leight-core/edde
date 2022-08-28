<?php
declare(strict_types=1);

namespace Edde\Source;

use Edde\Container\ContainerTrait;
use Edde\Log\LoggerTrait;
use Edde\Mapper\IMapper;
use Edde\Repository\IRepository;
use Edde\Source\Dto\QueryDto;
use Edde\Source\Dto\SourceQueryDto;
use Edde\Source\Mapper\NoopMapperTrait;
use Edde\Utils\ObjectUtils;
use Generator;
use League\Uri\Components\Query;
use League\Uri\Uri;
use MultipleIterator;
use Throwable;
use function array_filter;
use function array_keys;
use function array_map;
use function call_user_func;
use function explode;
use function get_class;
use function implode;
use function json_encode;
use function ltrim;
use function sprintf;
use function urldecode;

abstract class AbstractSource implements ISource {
	use ContainerTrait;
	use NoopMapperTrait;
	use LoggerTrait;

	/**
	 * @var IRepository[]
	 */
	protected $repositories;
	/**
	 * @var IMapper[]
	 */
	protected $mappers;
	/**
	 * @var QueryDto[]
	 */
	protected $queries;

	public function __construct(array $repositories, array $mappers, array $queries) {
		$this->repositories = $repositories;
		$this->mappers = $mappers;
		$this->queries = $queries;
	}

	public function query(string $query): Generator {
		yield from $this->source($this->parse($query));
	}

	public function source(SourceQueryDto $sourceQuery): Generator {
		yield from call_user_func([
			$this,
			$sourceQuery->type,
		], $sourceQuery);
	}

	public function group(array $queries): Generator {
		$this->logger->debug('Executing group query on a Source.');
		$this->logger->debug(json_encode($queries));
		/** @var $_queries SourceQueryDto[] */
		$_queries = array_map([
			$this,
			'parse',
		], $queries);
		$this->logger->debug('Queries parsed.');
		$this->logger->debug(json_encode($_queries));
		$sources = [];
		/**
		 * To keep the stuff optimal, we've to take only source queries to do only
		 * necessary requests to sources. Without this, request is made per query which
		 * could lead to database doom.
		 */
		foreach ($_queries as $query) {
			if ($query->source) {
				$sources[$query->source] = $query;
			}
		}
		$this->logger->debug('Prepared query sources.');
		/**
		 * Love this thing - magical SPL iterator which enables us to iterate over all sources at once with source
		 * name as a key and value from the underlying generator.
		 */
		$iterator = new MultipleIterator(MultipleIterator::MIT_NEED_ANY | MultipleIterator::MIT_KEYS_ASSOC);
		array_map(function (?SourceQueryDto $query) use ($iterator) {
			$this->logger->debug(sprintf('Attaching source [%s] of type [%s].', $query->source, $query->type));
			/**
			 * Trick: attach to a source, but take the value returned from source instead resolving value of the query.
			 * With this all queries with the same source do only one request instead of request per query which is highly suboptimal.
			 */
			$iterator->attachIterator($this->iterator(SourceQueryDto::create(['source' => $query->source])), $query->source);
		}, $sources);
		$this->logger->debug('Sources attached to the iterator.');
		$static = [];
		$iterations = 0;
		$this->logger->debug('Starting iterator.');
		try {
			foreach ($iterator as $items) {
				$this->logger->debug(sprintf('Running iteration [%d].', $iterations));
				$iterations++;
				/**
				 * This is another little trick - take values and keep them for "values" - that means literal values will be properly populated,
				 * also static values from the source will be properly populated; the rest will be filled by a generators.
				 */
				foreach ($items as $k => $v) {
					$static[$k] = $v ?: $static[$k];
				}
				$this->logger->debug(sprintf('Items [%s].', json_encode($items)));
				$this->logger->debug(sprintf('Static [%s].', json_encode($static)));

				/**
				 * The boring stuff:
				 * It's necessary to know which kind of value must be emitted; most part of the trick is that sources returns source object instead of
				 * resolved value, so it's done here.
				 * Also because source supports copying the same value over and over, it has to be handled here.
				 */
				yield array_map(
					function ($query) use ($items, $static) {
						$this->logger->debug(sprintf('Running query [%s::%s].', $query->source, $query->type));
						try {
							$mapper = isset($query->params['mapper']) ? $this->container->get($query->params['mapper']) : $this->noopMapper;
							$this->logger->debug(sprintf('Using mapper [%s], requested [%s].', get_class($mapper), $query->params['mapper'] ?? '- mapper not provided -'));
						} catch (Throwable $throwable) {
							$this->logger->error($throwable);
							$mapper = $this->noopMapper;
						}
						$this->logger->debug(sprintf('Resolving value of query type [%s].', $query->type));
						switch ($query->type) {
							/**
							 * Regular value from the source (generator), nothing to think about
							 */
							case 'iterator':
								$this->logger->debug(sprintf('Getting from iterator [%s::%s], value [%s], items [%s].', $query->source, $query->type, implode(', ', $query->value), implode(', ', array_keys((array)$items[$query->source]))));
								$value = isset($items[$query->source]) ? ObjectUtils::valueOf($items[$query->source], $query->value) : null;
								break;
							/**
							 * The shit in the box: reuse value taken from the first run of the generator and reuse it like a bitch
							 */
							case 'single':
								$this->logger->debug(sprintf('Resolved [%s::%s].', $query->source, $query->type));
								$value = isset($static[$query->source]) ? ObjectUtils::valueOf($static[$query->source], $query->value) : null;
								break;
							/**
							 * Most simple one - just vomit the value
							 */
							case 'static':
								$this->logger->debug('Resolved static value.');
								$value = $query->value;
								break;
						}
						$this->logger->debug(sprintf('Resolved value [%s].', isset($value) ? json_encode($value) : '- no value -'));
						return isset($value) ? $mapper->item([
							'value'  => $value,
							'params' => $query->params,
						]) : null;
					},
					$_queries
				);
			}
			$this->logger->debug(sprintf('Group iterator is done; iterations (yields) done [%d].', $iterations));
		} catch (Throwable $throwable) {
			$this->logger->error($throwable);
			throw $throwable;
		}
	}

	public function parse(string $query): SourceQueryDto {
		$result = [
			'type'  => 'static',
			'value' => $query,
		];
		try {
			$uri = Uri::createFromString($query);
			$query = Query::createFromUri($uri);
			$params = $query->params();
			switch ($type = ($uri->getUserInfo() ?? 'iterator')) {
				/**
				 * Single item from the source
				 */
				case 'single':
				case 'iterator':
					$result = [
						'type'   => $type,
						'source' => $uri->getHost(),
						'value'  => array_filter(explode('/', $uri->getPath())),
						'params' => $params,
					];
					break;
				/**
				 * Literal; does not take anything from source, just returns the same input
				 */
				case 'static':
					$result = [
						'type'   => $type,
						'value'  => urldecode(ltrim($uri->getPath(), '/')),
						'params' => $params,
					];
					break;
			}
		} finally {
			return SourceQueryDto::create($result);
		}
	}

	public function single(SourceQueryDto $sourceQuery): Generator {
		foreach ($this->iterator($sourceQuery) as $item) {
			yield $item;
			break;
		}
	}

	public function iterator(SourceQueryDto $sourceQuery): Generator {
		/** @var $mapper IMapper */
		$mapper = isset($sourceQuery->params['mapper']) ? $this->container->get($sourceQuery->params['mapper']) : $this->noopMapper;
		$this->logger->debug(sprintf('Running iterator on source [%s], mapper [%s].', $sourceQuery->source, $sourceQuery->params['mapper'] ?? '- no mapper provided -'));
		try {
			foreach ($this->repositories[$sourceQuery->source]->execute($this->queries[$sourceQuery->source]->query ?? null) as $item) {
				yield ObjectUtils::valueOf($this->mappers[$sourceQuery->source]->item($item), $sourceQuery->value);
			}
			$this->logger->debug(sprintf('Source [%s] iteration done.', $sourceQuery->source));
		} catch (Throwable $exception) {
			$this->logger->error($exception);
			throw $exception;
		}
	}

	public function static(SourceQueryDto $sourceQuery): Generator {
		yield $sourceQuery->value;
	}
}
