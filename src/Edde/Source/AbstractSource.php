<?php
declare(strict_types=1);

namespace Edde\Source;

use Edde\Mapper\IMapper;
use Edde\Repository\IRepository;
use Edde\Source\Dto\QueryDto;
use Edde\Source\Dto\SourceQueryDto;
use Generator;
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
		$_query = $this->parse($query);
		yield from call_user_func([
			$this,
			$_query->type,
		], $_query);
	}

	public function group(array $queries): Generator {
		$sources = [];
		foreach ($queries as $query) {
			$_query = $this->parse($query);
			if ($_query->source) {
				$sources[$_query->source] = $query;
			}
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
}
