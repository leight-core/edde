<?php
declare(strict_types=1);

namespace Edde\Source;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Container\ContainerTrait;
use Edde\Mapper\IMapper;
use Edde\Repository\IRepository;
use Edde\Source\Dto\QueriesDto;
use Edde\Source\Dto\SourcesDto;
use Edde\Source\Exception\SourceException;

abstract class AbstractSourceService implements ISourceService {
	use ContainerTrait;

	/**
	 * @param SourcesDto $sources
	 * @param QueriesDto $queries
	 *
	 * @return ISource
	 *
	 * @throws SourceException
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function source(SourcesDto $sources, QueriesDto $queries): ISource {
		$_repositories = [];
		$_mappers = [];
		$_queries = [];
		foreach ($sources->sources as $source) {
			if (!($_repositories[$source->name] = $this->container->get($source->source)) instanceof IRepository) {
				throw new SourceException(sprintf('Requested repository [%s] is not instance of [%s].', $source->source, IRepository::class));
			}
			if (!($_mappers[$source->name] = $this->container->get($source->mapper)) instanceof IMapper) {
				throw new SourceException(sprintf('Requested mapper [%s] is not instance of [%s].', $source->mapper, IMapper::class));
			}
		}
		foreach ($queries->queries as $query) {
			if (!isset($_repositories[$query->name])) {
				throw new SourceException(sprintf('Defined query [%s] for source not connected. Please add this source to [%s] parameter.', $query->name, Dto\SourcesDto::class));
			}
			$_queries[$query->name] = $query;
		}
		return new Source($_repositories, $_mappers, $_queries);
	}
}
