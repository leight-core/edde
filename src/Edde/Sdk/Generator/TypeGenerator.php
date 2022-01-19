<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use DateTime;
use Edde\Reflection\Dto\TemplateDto;
use Edde\Reflection\Dto\Type\AbstractType;
use Edde\Reflection\Dto\Type\Utils\IClassType;
use Edde\Reflection\Dto\Type\Utils\IGenericType;
use Edde\Reflection\Dto\Type\Utils\IMixedType;
use Edde\Reflection\Dto\Type\Utils\IScalarType;
use Edde\Reflection\Dto\Type\Utils\ITemplateType;
use Edde\Reflection\Dto\Type\Utils\IUnknownType;
use Edde\Reflection\Exception\MissingReflectionClassException;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Reflection\ReflectionServiceTrait;
use Edde\Sdk\NameResolverTrait;
use Edde\Sdk\SdkException;
use ReflectionException;
use function get_class;
use function implode;
use function sprintf;

class TypeGenerator {
	use ReflectionServiceTrait;
	use NameResolverTrait;

	/** @var string[] */
	protected $alias = [];

	public function reset() {
		$this->alias = [
			'mixed'         => 'any',
			'float'         => 'number',
			'double'        => 'number',
			'int'           => 'number',
			'bool'          => 'boolean',
			DateTime::class => 'string',
		];
	}

	public function alias(string $source, string $target) {
		$this->alias[$source] = $target;
	}

	/**
	 * @param AbstractType|null $type
	 * @param string            $default
	 *
	 * @return string
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 * @throws MissingReflectionClassException
	 */
	public function resolve(?AbstractType $type, string $default = 'void'): string {
		$export = $default;
		if (!$type) {
			return $export;
		}
		if ($type instanceof IScalarType) {
			$export = $type->type();
		} else if ($type instanceof IClassType) {
			$export = $this->alias[$type->class()] ?? "import(\"@/sdk/" . $type->module() . "\")." . $type->className();
		} else if ($type instanceof ITemplateType) {
			$export = $type->template()->name;
		} else if ($type instanceof IMixedType) {
			$export = 'mixed';
		} else if ($type instanceof IGenericType) {
			// @TODO("Remove condition after IGenericType will be subclass of IClassType")
			/** @var $classType IClassType */
			if (!($classType = $type->type()) instanceof IClassType) {
				throw new SdkException(sprintf('Type [%s] cannot be generic!', get_class($classType)));
			}
			$generics = [];
			$class = $this->reflectionService->toClass($classType->class());
			foreach ($type->generics() as $generic) {
				$generics[] = $this->resolve($generic);
			}
			$defaults = array_filter($class->templates ?? [], function (TemplateDto $templateDto) {
				return $templateDto->default !== null;
			});
			if ($class->hasTemplates && count($generics + $defaults) !== count($class->templates)) {
				throw new SdkException(sprintf("Missing templates for class [%s].", $class->fqdn));
			}
			$export = $this->resolve($type->type());
			$export .= "<";
			$export .= implode(", ", $generics);
			$export .= ">";
		} else if ($type instanceof IUnknownType) {
			throw new SdkException(sprintf("Unsupported unknown type [%s (%s)]!", get_class($type), $type->type()));
		} else {
			throw new SdkException(sprintf("Unsupported type [%s]!", get_class($type)));
		}
		return ($this->alias[$export] ?? $export) . ($type->isArray ? '[]' : '') . ($type->isRequired ? '' : ' | null') . ($type->isOptional ? ' | undefined' : '');
	}
}
