<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Schema\ISchema;
use Edde\Sdk\AbstractExport;

class SchemaExport extends AbstractExport {
	/**
	 * @var ISchema
	 */
	protected $schema;

	public function withSchema(ISchema $schema): SchemaExport {
		$this->schema = $schema;
		return $this;
	}

	public function getSchemaName(ISchema $schema): string {
		return str_replace('Schema', '', substr(strrchr($schema->getName(), '\\'), 1));
	}

	protected function toZod(ISchema $schema): string {
		$zod = [];
		foreach ($schema->getAttributes() as $attribute) {
			$type = "z.any()";
			switch ($attribute->getType()) {
				case 'string':
					$type = "z.string()";
					$attribute->isRequired() && ($type .= ".nonempty({message: 'Non-empty'})");
					break;
				case 'int':
					$type = "z.number()";
					break;
			}
			if ($attribute->hasSchema()) {
				$type = $this->getSchemaName($attribute->getSchema()) . 'Schema';
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
})
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
		$imports = [
			'import {z} from "@leight/utils";',
		];
		foreach ($this->schema->getAttributes() as $attribute) {
			if ($attribute->hasSchema()) {
				$schema = $this->getSchemaName($attribute->getSchema()) . 'Schema';
				$imports[] = sprintf('import {%s} from "./%s";', $schema, $schema);
			}
		}
		return $this->toExport([
			$this->toExport($imports, "\n"),
			$this->toZodSchema($this->schema),
		]);
	}
}
