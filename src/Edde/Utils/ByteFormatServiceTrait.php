<?php
declare(strict_types=1);

namespace Edde\Utils;

trait ByteFormatServiceTrait {
	/** @var ByteFormatService */
	protected $byteFormatService;

	/**
	 * @Inject
	 *
	 * @param ByteFormatService $byteFormatService
	 */
	public function setByteFormatService(ByteFormatService $byteFormatService): void {
		$this->byteFormatService = $byteFormatService;
	}
}
