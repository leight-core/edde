<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Schema\SchemaLoaderTrait;
use Edde\Sdk\AbstractExport;

class SchemaExport extends AbstractExport {
	use SchemaLoaderTrait;

	/**
	 * @var string|null
	 */
	protected $schema;

	public function withSchema(?string $schema): SchemaExport {
		$this->schema = $schema;
		return $this;
	}

	function export(): ?string {
		return 'schema';
	}
}
