<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Http\HttpIndexTrait;
use Edde\Link\LinkGeneratorTrait;
use Edde\Reflection\Exception\UnknownTypeException;
use Edde\Rest\Reflection\Endpoint;
use Edde\Sdk\Generator\ModuleGeneratorTrait;
use Edde\Sdk\Generator\TypeGeneratorTrait;
use Generator;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use ReflectionException;

class SdkGenerator {
	use LinkGeneratorTrait;
	use HttpIndexTrait;
	use ModuleGeneratorTrait;
	use TypeGeneratorTrait;

	/**
	 * @param Endpoint[] $endpoints
	 *
	 * @return Generator|string[]
	 *
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 */
	public function generate(array $endpoints): Generator {
		$this->typeGenerator->reset();
		yield from $this->moduleGenerator->generate($endpoints);
	}

	/**
	 * @throws ReflectionException
	 * @throws SdkException
	 * @throws UnknownTypeException
	 * @throws ZipException
	 */
	public function zip() {
		$zip = new ZipFile();
		foreach ($this->generate($this->httpIndex->endpoints()) as $path => $source) {
			$zip->addFromString("sdk/$path", $source);
		}
		$zip->outputAsAttachment('sdk.zip');
	}
}
