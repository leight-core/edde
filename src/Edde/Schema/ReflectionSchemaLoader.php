<?php
declare(strict_types=1);

namespace Edde\Schema;

use Edde\Dto\Mapper\InputTypeMapper;
use Edde\Dto\Mapper\OutputTypeMapper;
use ReflectionClass;
use ReflectionException;
use Throwable;
use function is_array;
use function is_string;

class ReflectionSchemaLoader extends AbstractSchemaLoader implements ISchemaLoader {
	/** @var ISchema[] */
	protected $schemas = [];
	/** @var bool[] */
	protected $loading = [];

	/** @inheritdoc */
	public function load(string $schema): ISchema {
		try {
			if (isset($this->schemas[$schema])) {
				return $this->schemas[$schema];
			}
			if (isset($this->loading[$schema])) {
				throw new SchemaException(sprintf('Detected cyclic dependency on [%s]; this schema is already in loading process [%s].', $schema, implode(' -> ', array_keys($this->loading))));
			}
			$this->loading[$schema] = true;

			/**
			 * Schema "global" modifiers (on the schema level).
			 */
			$isPartial = false;
			$overrideRequired = [];

			$reflectionClass = new ReflectionClass($schema);
			$schemaBuilder = new SchemaBuilder($schema);
			foreach ($reflectionClass->getConstants() as $name => $value) {
				switch ($name) {
					case 'meta':
						if (is_array($value) === false) {
							throw new SchemaException(sprintf('Meta for schema [%s] must be an array.', $schema));
						}
						$schemaBuilder->meta($value);
						break;
					case 'partial':
						$isPartial = $value;
						break;
					case 'required':
						$overrideRequired = $value;
						break;
					default:
						throw new SchemaException(sprintf('Unknown directive (constant) in schema [%s::%s] must be an array.', $schema, $name));
				}
			}
			/**
			 * go through all methods as they're used as schema definition
			 */
			foreach ($reflectionClass->getMethods() as $reflectionMethod) {
				$attributeBuilder = $schemaBuilder->attribute($attributeName = $reflectionMethod->getName());
				/**
				 * set default property type to a string
				 */
				$attributeBuilder->type($propertyType = 'mixed');
				$input = null;
				$output = null;
				if (($type = $reflectionMethod->getReturnType()) !== null) {
					$attributeBuilder->type($propertyType = $type->getName());
					$attributeBuilder->required($type->allowsNull() === false);
				}
				foreach ($reflectionMethod->getParameters() as $parameter) {
					switch ($parameterName = $parameter->getName()) {
						case 'type':
							try {
								if (is_string($type = $parameter->getDefaultValue()) === false) {
									throw new ReflectionException('Type name is not a string value.');
								}
							} catch (ReflectionException $exception) {
								throw new SchemaException(sprintf('Parameter [%s::%s($type)] must have a default string value as a type name.', $schema, $attributeName), 0, $exception);
							}
							$attributeBuilder->type($type);
							$attributeBuilder->required(!$parameter->isOptional());
							$propertyType = $type;
							break;
						case 'default':
							$attributeBuilder->default($parameter->getDefaultValue());
							break;
						case 'required':
							$attributeBuilder->required($parameter->getDefaultValue());
							break;
						case 'load':
							$attributeBuilder->load($parameter->getDefaultValue());
							$attributeBuilder->schema($this->load($propertyType));
							break;
						case 'schema':
							$attributeBuilder->load();
							$attributeBuilder->schema($this->load($parameter->getDefaultValue()));
							break;
						case 'array':
						case 'isArray':
							$attributeBuilder->array($parameter->getDefaultValue());
							break;
						case 'instanceOf':
							$attributeBuilder->instanceOf($parameter->getDefaultValue());
							break;
						case 'meta':
							$attributeBuilder->meta($parameter->getDefaultValue());
							break;
						case 'input':
							$input = $parameter->getDefaultValue();
							break;
						case 'output':
							$output = $parameter->getDefaultValue();
							break;
						case 'internal':
							$attributeBuilder->internal($parameter->getDefaultValue());
							break;
						default:
							throw new SchemaException(sprintf('Unknown schema [%s::%s] directive [%s].', $schema, $attributeName, $parameterName));
					}
				}
				$attributeBuilder->output($output ?? OutputTypeMapper::class);
				$attributeBuilder->input($input ?? InputTypeMapper::class);
			}

			if ($isPartial) {
				foreach ($schemaBuilder->attributes() as $attributeBuilder) {
					$attributeBuilder->required(false);
				}
			}
			if (!empty($overrideRequired)) {
				foreach ($overrideRequired as $k => $v) {
					if (!$schemaBuilder->has($k)) {
						throw new SchemaException(sprintf("Overriding 'required' of unknown schema attribute [%s::%s].", $schema, $k));
					}
					$schemaBuilder->attribute($k)->required($v);
				}
			}

			return $this->schemas[$schema] = $schemaBuilder->create();
		} catch (SchemaException $exception) {
			throw $exception;
		} catch (Throwable $throwable) {
			throw new SchemaException(sprintf('Cannot do schema reflection of [%s]: %s', $schema, $throwable->getMessage()), 0, $throwable);
		} finally {
			$this->loading[$schema] = false;
		}
	}
}
