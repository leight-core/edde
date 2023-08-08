<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Rpc\Service\RpcServiceTrait;
use Edde\Schema\SchemaLoaderTrait;

abstract class AbstractGenerator implements IGenerator {
	use RpcServiceTrait;
	use RpcHandlerIndexTrait;
	use SchemaLoaderTrait;
	use ContainerTrait;

	protected $output;

	public function withOutput(string $output): self {
		$this->output = $output;
		return $this;
	}

	protected function mkdir(string $dir): void {
		@mkdir($dir, 0777, true);
	}

	protected function makeOutput(?string $output = null): self {
		$this->mkdir(sprintf("%s%s", $this->output, $output ? '/' . $output : ''));
		return $this;
	}

	protected function writeTo(string $file, $content, int $flags = 0) {
		if (!$content) {
			return;
		}
		$this->makeOutput(dirname($file));
		file_put_contents(sprintf('%s/%s', $this->output, $file), $content, $flags);
	}
}
