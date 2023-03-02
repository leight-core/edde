<?php
declare(strict_types=1);

namespace Edde\Schema;

use DateTime;
use ReflectionClass;
use ReflectionException;
use Throwable;
use function is_array;
use function is_string;

class ReflectionSchemaLoader extends AbstractSchemaLoader implements ISchemaLoader {
	/** @inheritdoc */
	public function load(string $schema): ISchema {
		try {
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
					default:
						throw new SchemaException(sprintf('Unknown directive (constant) in schema [%s::%s] must be an array.', $schema, $name));
				}
			}
			/**
			 * go through all methods as they're used as schema definition
			 */
			foreach ($reflectionClass->getMethods() as $reflectionMethod) {
				$attributeBuilder = $schemaBuilder->attribute($propertyName = $reflectionMethod->getName());
				/**
				 * set default property type to a string
				 */
				$attributeBuilder->type($propertyType = 'string');
				if (($type = $reflectionMethod->getReturnType()) !== null) {
					$attributeBuilder->type($propertyType = $type->getName());
					$attributeBuilder->required($type->allowsNull() === false);
				}
				foreach ($reflectionMethod->getParameters() as $parameter) {
					switch ($parameterName = $parameter->getName()) {
						case 'filter':
							try {
								$filter = $parameter->getDefaultValue();
								if (is_string($filter) === false) {
									throw new ReflectionException('Filter name is not a string value.');
								}
							} catch (ReflectionException $exception) {
								throw new SchemaException(sprintf('Parameter [%s::%s($filter)] must have a default string value as a filter name.', $schema, $propertyName), 0, $exception);
							}
							$attributeBuilder->filter($parameterName, $filter);
							break;
						case 'validator':
							try {
								$validator = $parameter->getDefaultValue();
								if (is_string($validator) === false) {
									throw new ReflectionException('Validator name is not a string value.');
								}
							} catch (ReflectionException $exception) {
								throw new SchemaException(sprintf('Parameter [%s::%s($validator)] must have a default string value as a validator name.', $schema, $propertyName), 0, $exception);
							}
							$attributeBuilder->validator($validator);
							break;
						case 'type':
							try {
								$type = $parameter->getDefaultValue();
								if (is_string($type) === false) {
									throw new ReflectionException('Type name is not a string value.');
								}
							} catch (ReflectionException $exception) {
								throw new SchemaException(sprintf('Parameter [%s::%s($type)] must have a default string value as a type name.', $schema, $propertyName), 0, $exception);
							}
							$attributeBuilder->type($type);
							$attributeBuilder->required($parameter->isOptional());
							$propertyType = $type;
							break;
						case 'default':
							$attributeBuilder->default($parameter->getDefaultValue());
							break;
						case 'required':
							$attributeBuilder->required($parameter->getDefaultValue());
							break;
						default:
							throw new SchemaException(sprintf('Unknown schema [%s::%s] directive [%s].', $schema, $propertyName, $propertyName));
					}
				}
				switch ($propertyType) {
					case 'float':
					case 'int':
					case 'bool':
					case 'string':
					case 'uuid':
					case 'datetime':
					case 'json':
					case 'binary':
					case DateTime::class:
						$attributeBuilder->filter('type', $propertyType);
						$attributeBuilder->validator($propertyType);
						break;
				}
			}
			return $schemaBuilder->create();
		} catch (SchemaException $exception) {
			throw $exception;
		} catch (Throwable $throwable) {
			throw new SchemaException(sprintf('Cannot do schema reflection of [%s]: %s', $schema, $throwable->getMessage()), 0, $throwable);
		}
	}
}
