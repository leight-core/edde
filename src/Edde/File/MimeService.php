<?php
declare(strict_types=1);

namespace Edde\File;

use League\MimeTypeDetection\FinfoMimeTypeDetector;
use function pathinfo;
use const PATHINFO_EXTENSION;

class MimeService {
	/** @var FinfoMimeTypeDetector */
	protected $mimeDetector;

	public function __construct() {
		$this->mimeDetector = new FinfoMimeTypeDetector();
	}

	public function detect(string $file): ?string {
		static $map = [
			'svg'   => 'image/svg+xml',
			'png'   => 'image/png',
			'js'    => 'application/javascript; charset=utf-8',
			'woff2' => 'font/woff2',
			'css'   => 'text/css; charset=utf-8',
			'html'  => 'text/html; charset=utf-8',
		];

		return $map[pathinfo($file, PATHINFO_EXTENSION) ?? null] ?? $this->mimeDetector->detectMimeTypeFromFile($file);
	}
}
