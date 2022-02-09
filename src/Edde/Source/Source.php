<?php
declare(strict_types=1);

namespace Edde\Source;

use Edde\Source\Dto\SourceQueryDto;
use Edde\Utils\ObjectUtils;
use Generator;

class Source extends AbstractSource {
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
