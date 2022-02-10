<?php
declare(strict_types=1);

namespace Edde\Source;

use Edde\Mapper\IMapper;
use Edde\Repository\IRepository;
use Edde\Source\Dto\QueryDto;
use Edde\Source\Dto\SourceQueryDto;
use Edde\Utils\ObjectUtils;
use Generator;
use MultipleIterator;
use function array_map;
use function array_slice;
use function call_user_func;
use function explode;
use function substr;

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
		foreach ($_queries as $query) {
			if ($query->source) {
				$sources[$query->source] = $query;
			}
		}
		$iterator = new MultipleIterator(MultipleIterator::MIT_NEED_ANY | MultipleIterator::MIT_KEYS_ASSOC);
		array_map(function (?SourceQueryDto $query) use ($iterator) {
			$iterator->attachIterator($this->query("$." . $query->source), $query->source);
		}, $sources);
		$static = [];
		foreach ($iterator as $items) {
			/**
			 * Update & keep values which may be missing. This could be a bug when one iterator ends up earlies, so
			 * it's latest data could be repeated unexpected.
			 */
			foreach ($items as $k => $v) {
				$static[$k] = $v ?: $static[$k];
			}
			yield array_map(
				function ($query) use ($items, $static) {
					switch ($query->type) {
						case 'iterator':
							return isset($items[$query->source]) ? ObjectUtils::valueOf($items[$query->source], $query->value) : null;
						case 'value':
							return isset($static[$query->source]) ? ObjectUtils::valueOf($static[$query->source], $query->value) : null;
						case 'literal':
							return $query->value;
					}
				},
				$_queries
			);
		}
	}

	public function parse(string $query): SourceQueryDto {
		$result = [
			'type'  => 'literal',
			'value' => $query,
		];
		switch (substr($query, 0, 2)) {
			/**
			 * Single item from the source
			 */
			case '#.':
				$explode = explode('.', $query);
				$result = [
					'type'   => 'value',
					'source' => $explode[1],
					'value'  => array_slice($explode, 2),
				];
				break;
			/**
			 * Iterate through all data available in the source
			 */
			case '$.':
				$explode = explode('.', $query);
				$result = [
					'type'   => 'iterator',
					'source' => $explode[1],
					'value'  => array_slice($explode, 2),
				];
				break;
			/**
			 * Literal; does not take anything from source, just returns the same input
			 */
			case '&.':
				$explode = explode('.', $query);
				$result = [
					'type'  => 'literal',
					'value' => $explode[1],
				];
				break;
		}
		return SourceQueryDto::create($result);
	}

	public function value(SourceQueryDto $sourceQuery): Generator {
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

	public function literal(SourceQueryDto $sourceQuery): Generator {
		yield $sourceQuery->value;
	}
}
