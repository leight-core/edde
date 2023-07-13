<?php
declare(strict_types=1);

namespace Edde\Sdk;

abstract class AbstractGenerator implements IGenerator {
	protected $output;

	public function withOutput(string $output): self {
		$this->output = $output;
		return $this;
	}

	protected function makeOutput(): self {
		@mkdir($this->output, 0777, true);
		return $this;
	}
}
