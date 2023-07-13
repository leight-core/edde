<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\IRpcHandler;
use Edde\Schema\SchemaLoaderTrait;
use Edde\Sdk\AbstractExport;

class SchemaExport extends AbstractExport {
	use SchemaLoaderTrait;

	/**
	 * @var IRpcHandler
	 */
	protected $handler;

	public function withHandler(IRpcHandler $handler): SchemaExport {
		$this->handler = $handler;
		return $this;
	}

	function export(): ?string {
		$export = [];
		$export[] = `import {z} from "@leight/utils";`;

		if (($name = $this->handler->getRequestSchema()) && $schema = $this->schemaLoader->load($name)) {
			$schemaName = $schema->getMeta('export', 'Request');
			$export[] = `
export const ${schemaName}Schema = z.object({});
export type I${schemaName}Schema = typeof $schemaName;
export type I${schemaName} = z.infer<I${schemaName}Schema>;
`;
		}
		if (($name = $this->handler->getResponseSchema()) && $schema = $this->schemaLoader->load($name)) {
			$schemaName = $schema->getMeta('export', 'Request');
			$export[] = `
export const ${schemaName}Schema = z.object({});
export type I${schemaName}Schema = typeof $schemaName;
export type I${schemaName} = z.infer<I${schemaName}Schema>;
`;
		}

		return $this->toExport($export);
	}
}
