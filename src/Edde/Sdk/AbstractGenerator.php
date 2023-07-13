<?php
declare(strict_types=1);

namespace Edde\Sdk;

abstract class AbstractGenerator implements IGenerator {
	protected $output;

	public function withOutput(string $output): self {
		$this->output = $output;
		return $this;
	}

	protected function mkdir(string $dir): void {
		@mkdir($dir, 0777, true);
	}

	protected function makeOutput(): self {
		$this->mkdir($this->output);
		return $this;
	}
}
