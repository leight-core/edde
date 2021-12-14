<?php
declare(strict_types=1);

namespace Edde\Pdf;

use Edde\Pdf\Dto\Template\TemplateDto;
use Edde\Uuid\UuidServiceTrait;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use setasign\Fpdi\Tfpdf\Fpdi;
use Symfony\Component\Yaml\Yaml;

class PdfTemplateService {
	use UuidServiceTrait;

	/**
	 * @param TemplateDto $templateDto
	 *
	 * @return string filename of the generated PDF file (if provided, should be same as in TemplateDto)
	 *
	 * @throws CrossReferenceException
	 * @throws FilterException
	 * @throws PdfParserException
	 * @throws PdfReaderException
	 * @throws PdfTypeException
	 * @throws ZipException
	 */
	public function template(TemplateDto $templateDto): string {
		$zip = new ZipFile();
		$zip->openFile($templateDto->source);

		$yaml = Yaml::parse($zip->getEntryContents('template.yml'));
		$font = $yaml['font'] ?? 'DejaVu';
		$file = $templateDto->target ?? tempnam('pdf-template', 'pdf');

		$pdfi = new Fpdi();
		$pdfi->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);

		$lines = $yaml['lines'] ?? null;

		for ($i = 1; $i < $pdfi->setSourceFile($zip->getEntryStream('template.pdf')) + 1; $i++) {
			$pdfi->AddPage();
			$pdfi->useTemplate($pdfi->importPage($i));

			if ($lines && [
					$x,
					$y,
				] = $lines) {

				$pdfi->SetDrawColor(195, 195, 195);
				$pdfi->SetFont($font, '', 5);
				$limit = 300;
				for ($line = 0; $line < $limit; $line += $x) {
					$pdfi->Text($line + 0.2, 2.5, $line);
					$pdfi->Line($line, 0, $line, $limit);
				}
				for ($line = 0; $line < $limit; $line += $y) {
					$pdfi->Text(0, $line - 0.3, $line);
					$pdfi->Line(0, $line, $limit, $line);
				}
			}

			$pdfi->SetDrawColor(0, 0, 0);
			foreach ($yaml['pages'][$i - 1] ?? [] as $item) {
				$pdfi->SetFont($font, '', 12);
				if (!($value = ($templateDto->values[$item['name']] ?? null))) {
					continue;
				}
				if ($item['cross'] && $cross = $yaml['cross'][$item['cross']] ?? null) {
					[
						$x,
						$y,
					] = $item['coords'];
					[
						$x1,
						$y1,
					] = $cross['size'];
					[
						$relativeX,
						$relativeY,
					] = $cross['relative'] ?? [
						0,
						0,
					];

					$pdfi->Line(
						$x + $relativeX,
						$y + $relativeY,
						$x + $x1 + $relativeX,
						$y + $y1 + $relativeY
					);
					$pdfi->Line(
						$x + $relativeX,
						$y + $y1 + $relativeY,
						$x + $x1 + $relativeX,
						$y + $relativeY
					);
					continue;
				}
				$pdfi->SetXY($item['coords'][0], $item['coords'][1]);
				if ($pad = ($item['pad-left'] ?? null)) {
					$value = str_pad((string)$value, $pad['count'], (string)$pad['char'], STR_PAD_LEFT);
				}
				if ($fontSize = ($item['font-size'] ?? null)) {
					$pdfi->SetFont($font, '', $fontSize);
				}
				if ($item['limit']) {
					$value = substr((string)$value, 0, (int)$item['limit']);
				}
				foreach ($item['sizes'] ?? [] as $size) {
					if (strlen($value) >= $size['length']) {
						$pdfi->SetFont($font, '', $size['size'] ?? 12);
					}
				}
				$pdfi->Write(0, $value);
			}
		}
		$pdfi->Output($file, 'F', true);
		return $file;
	}
}
