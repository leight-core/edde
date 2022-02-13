<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Dto\Export\MetaDto;
use Edde\File\Dto\FileDto;
use Edde\Source\Dto\QueriesDto;

interface IExcelExportService {
	/**
	 * Get the export meta file.
	 *
	 * @param string $file
	 *
	 * @return MetaDto
	 */
	public function meta(string $file): MetaDto;

	/**
	 * @param QueriesDto $queries
	 * @param string     $template
	 * @param string     $target
	 *
	 * @return FileDto
	 */
	public function export(QueriesDto $queries, string $template, string $target): FileDto;
}
