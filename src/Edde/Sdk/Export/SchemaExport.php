<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\IRpcHandler;
use Edde\Schema\ISchema;
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

	protected function toZod(ISchema $schema): string {
		$zod = [];
		foreach ($schema->getAttributes() as $attribute) {
			switch ($attribute->getType()) {
				case 'string':
					$zod[] = sprintf("z.string()%s,", $attribute->isRequired() ? '' : '.optional()');
					break;
				default:
					$zod[] = "z.any()";
			}
		}
		$zod = implode("\n\t", $zod);
		return <<<E
z.object({
$zod
});
E;

	}

	public function export(): ?string {
		$export = [];
		$export[] = <<<E
import {z} from "@leight/utils";
E;

		if (($name = $this->handler->getRequestSchema()) && $schema = $this->schemaLoader->load($name)) {
			$schemaName = $schema->getMeta('export', 'Request');
			$export[] = <<<E
export const ${schemaName}Schema = {$this->toZod($schema)};
export type I${schemaName}Schema = typeof $schemaName;
export type I${schemaName} = z.infer<I${schemaName}Schema>;
E;
		}
		if (($name = $this->handler->getResponseSchema()) && $schema = $this->schemaLoader->load($name)) {
			$schemaName = $schema->getMeta('export', 'Request');
			$export[] = <<<E
export const ${schemaName}Schema = {$this->toZod($schema)};
export type I${schemaName}Schema = typeof $schemaName;
export type I${schemaName} = z.infer<I${schemaName}Schema>;
E;
		}

		return $this->toExport($export);
	}
}
