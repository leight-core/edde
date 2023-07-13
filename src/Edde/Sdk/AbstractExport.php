<?php
declare(strict_types=1);

namespace Edde\Sdk;

abstract class AbstractExport implements IExport {
	protected function toExport(array $export): string {
		return implode(
			"\n",
			array_map(
				'trim',
				array_filter($export)
			)
		);
	}
}
