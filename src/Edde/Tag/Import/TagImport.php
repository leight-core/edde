<?php
declare(strict_types=1);

namespace Edde\Tag\Import;

use Edde\Import\AbstractImporter;
use Edde\Tag\Repository\TagRepositoryTrait;

class TagImport extends AbstractImporter {
	use TagRepositoryTrait;

	public function handle($item) {
		return $this->tagRepository->ensure($item['code'], $item['group'], (int)($item['sort'] ?? 0));
	}
}
