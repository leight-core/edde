<?php
declare(strict_types=1);

namespace Edde\Sdk;

interface IExport {
	public function export(): ?string;
}
