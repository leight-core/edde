<?php
declare(strict_types=1);

namespace Edde\File;

trait MimeServiceTrait {
	/** @var MimeService */
	protected $mimeService;

	/**
	 * @Inject
	 *
	 * @param MimeService $mimeService
	 */
	public function setMimeService(MimeService $mimeService): void {
		$this->mimeService = $mimeService;
	}
}
