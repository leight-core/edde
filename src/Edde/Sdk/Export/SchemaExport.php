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
		if ($orderBy = $schema->getMeta('orderBy')) {
			return sprintf('z.record(z.enum([%s]), z.enum(["asc", "desc"]))', implode(', ', array_map(function ($item) {
				return sprintf('"%s"', $item);
			}, $orderBy)));
		}

		$zod = [];
		foreach ($schema->getAttributes() as $attribute) {
			$type = "z.any()";
			switch ($attribute->getType()) {
				case 'string':
					$type = "z.string()";
					$attribute->isRequired() && ($type .= ".nonempty({message: 'Non-empty'})");
					break;
				case 'bool':
					$type = "z.boolean()";
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
					($attribute->isRequired() ? "z.array($type)" : "z.array($type).nullish()")
					: ($attribute->isRequired() ? $type : "$type.nullish()")
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
		if ($import = $schema->getMeta('import')) {
			return implode("\n", array_map(function ($import, $package) {
				return sprintf("export {%s} from \"%s\";", $import, $package);
			}, array_keys($import), $import));
		}
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
		if ($this->schema->getMeta('import')) {
			$imports = [];
		} else {
			foreach ($this->schema->getAttributes() as $attribute) {
				if ($attribute->hasSchema()) {
					$schema = $this->getSchemaName($attribute->getSchema()) . 'Schema';
					$imports[$schema] = sprintf('import {%s} from "./%s";', $schema, $schema);
				}
			}
		}
		return $this->toExport([
			$this->toExport($imports, "\n"),
			$this->toZodSchema($this->schema),
		]);
	}
}
