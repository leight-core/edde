<?php
declare(strict_types=1);

namespace Edde\Sdk;

abstract class AbstractExport implements IExport {
	protected function toExport(array $export, string $separator = "\n\n"): string {
		return implode(
			$separator,
			array_map(
				'trim',
				array_filter($export)
			)
		);
	}
}
