<?php
declare(strict_types=1);

namespace Edde\Source;

use Edde\Mapper\IMapper;
use Edde\Repository\IRepository;
use Edde\Source\Dto\QueryDto;
use Edde\Source\Dto\SourceQueryDto;
use Edde\Utils\ObjectUtils;
use Generator;
use League\Uri\Uri;
use MultipleIterator;
use function array_filter;
use function array_map;
use function call_user_func;
use function explode;

abstract class AbstractSource implements ISource {
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
		/** @var $_queries SourceQueryDto[] */
		$_queries = array_map([
			$this,
			'parse',
		], $queries);
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
		/**
		 * Love this thing - magical SPL iterator which enables us to iterate over all sources at once with source
		 * name as a key and value from the underlying generator.
		 */
		$iterator = new MultipleIterator(MultipleIterator::MIT_NEED_ANY | MultipleIterator::MIT_KEYS_ASSOC);
		array_map(function (?SourceQueryDto $query) use ($iterator) {
			/**
			 * Trick: attach to a source, but take the value returned from source instead resolving value of the query.
			 * With this all queries with the same source do only one request instead of request per query which is highly suboptimal.
			 */
			$iterator->attachIterator($this->iterator(SourceQueryDto::create(['source' => $query->source])), $query->source);
		}, $sources);
		$static = [];
		foreach ($iterator as $items) {
			/**
			 * This is another little trick - take values and keep them for "values" - that means literal values will be properly populated,
			 * also static values from the source will be properly populated; the rest will be filled by a generators.
			 */
			foreach ($items as $k => $v) {
				$static[$k] = $v ?: $static[$k];
			}
			/**
			 * The boring stuff:
			 * It's necessary to know which kind of value must be emitted; most part of the trick is that sources returns source object instead of
			 * resolved value, so it's done here.
			 * Also because source supports copying the same value over and over, it has to be handled here.
			 */
			yield array_map(
				function ($query) use ($items, $static) {
					switch ($query->type) {
						/**
						 * Regular value from the source (generator), nothing to think about
						 */
						case 'iterator':
							return isset($items[$query->source]) ? ObjectUtils::valueOf($items[$query->source], $query->value) : null;
						/**
						 * The shit in the box: reuse value taken from the first run of the generator and reuse it like a bitch
						 */
						case 'single':
							return isset($static[$query->source]) ? ObjectUtils::valueOf($static[$query->source], $query->value) : null;
						/**
						 * Most simple one - just vomit the value
						 */
						case 'static':
							return $query->value;
					}
				},
				$_queries
			);
		}
	}

	public function parse(string $query): SourceQueryDto {
		$result = [
			'type'  => 'static',
			'value' => $query,
		];
		try {
			$uri = Uri::createFromString($query);
			switch ($type = ($uri->getUserInfo() ?? 'iterator')) {
				/**
				 * Single item from the source
				 */
				case 'single':
					$result = [
						'type'   => $type,
						'source' => $uri->getHost(),
						'value'  => array_filter(explode('/', $uri->getPath())),
					];
					break;
				/**
				 * Iterate through all data available in the source
				 */
				case 'iterator':
					$explode = explode('.', $query);
					$result = [
						'type'   => $type,
						'source' => $uri->getHost(),
						'value'  => array_filter(explode('/', $uri->getPath())),
					];
					break;
				/**
				 * Literal; does not take anything from source, just returns the same input
				 */
				case 'static':
					$result = [
						'type'  => $type,
						'value' => $uri->getPath(),
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
		foreach ($this->repositories[$sourceQuery->source]->execute($this->queries[$sourceQuery->source]->query ?? null) as $item) {
			yield ObjectUtils::valueOf($this->mappers[$sourceQuery->source]->item($item), $sourceQuery->value);
		}
	}

	public function static(SourceQueryDto $sourceQuery): Generator {
		yield $sourceQuery->value;
	}
}
