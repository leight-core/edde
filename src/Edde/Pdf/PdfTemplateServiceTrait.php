<?php
declare(strict_types=1);

namespace Edde\Pdf;

/**
 * This is a simple service used to fill an existing PDF file with data
 * defined in the template (PDF as a source, template as description where and what to put).
 */
trait PdfTemplateServiceTrait {
	/** @var PdfTemplateService */
	protected $pdfTemplateService;

	/**
	 * @Inject
	 *
	 * @param PdfTemplateService $pdfTemplateService
	 */
	public function setPdfTemplateService(PdfTemplateService $pdfTemplateService): void {
		$this->pdfTemplateService = $pdfTemplateService;
	}
}
