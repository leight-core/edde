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

	protected function getSchemaName(ISchema $schema): string {
		return str_replace('Schema', '', substr(strrchr($schema->getName(), '\\'), 1));
	}

	protected function toZod(ISchema $schema): string {
		$zod = [];
		foreach ($schema->getAttributes() as $attribute) {
			$type = "z.any()";
			switch ($attribute->getType()) {
				case 'string':
					$type = "z.string()";
					break;
				case 'int':
					$type = "z.number()";
					break;
			}
			$zod[] = sprintf(
				'%s: %s,',
				$attribute->getName(),
				$attribute->isArray() ?
					($attribute->isRequired() ? "z.array($type)" : "z.array($type).optional()")
					: ($attribute->isRequired() ? $type : "$type.optional()")
			);
		}
		$zod = implode("\n\t", $zod);
		return <<<E
z.object({
	$zod
});
E;

	}

	protected function toZodSchema(ISchema $schema): string {
		$schemaName = $this->getSchemaName($schema);
		return <<<E
export const ${schemaName}Schema = {$this->toZod($schema)};
export type I${schemaName}Schema = typeof {$schemaName}Schema;
export type I${schemaName} = z.infer<I${schemaName}Schema>;
E;
	}

	public function export(): ?string {
		$export = [];
		$export[] = <<<E
import {z} from "@leight/utils";
E;

		if (($name = $this->handler->getRequestSchema()) && $schema = $this->schemaLoader->load($name)) {
			$export[] = $this->toZodSchema($schema);
		}
		if (($name = $this->handler->getResponseSchema()) && $schema = $this->schemaLoader->load($name)) {
			$export[] = $this->toZodSchema($schema);
		}

		return $this->toExport($export);
	}
}
