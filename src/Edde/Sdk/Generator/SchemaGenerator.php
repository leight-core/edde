<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Container\ContainerTrait;
use Edde\Rpc\Service\IRpcHandler;
use Edde\Schema\SchemaLoaderTrait;
use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\SchemaExport;

class SchemaGenerator extends AbstractGenerator {
	use ContainerTrait;
	use SchemaLoaderTrait;

	/**
	 * @var IRpcHandler
	 */
	protected $handler;

	public function withHandler(IRpcHandler $handler): SchemaGenerator {
		$this->handler = $handler;
		return $this;
	}

	public function generate(): ?string {
		printf("\t\tGenerating schemas to [%s]\n", $this->output);
		$this->makeOutput();
		$this->container->injectOn($schemaExport = new SchemaExport());
		if ($export = $schemaExport->withSchema($schema = $this->handler->getRequestSchema())->export()) {
			$schema = $this->schemaLoader->load($schema);
			printf("\t\t\t- Generating schema [%s]\n", $file = sprintf('%s/%s.ts', $this->output, $schema->getMeta('module', 'request')));
			file_put_contents($file, $export);
		}
		return null;
	}
}
