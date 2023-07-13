<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Reflection\Exception\UnknownTypeException;
use Generator;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use ReflectionException;

class SdkGenerator {
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
