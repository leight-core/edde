<?php
declare(strict_types=1);

namespace Edde\Progress;

use Edde\Slim\SlimApp;

class NoProgress extends AbstractProgress {
	static public function ensure(?IProgress $progress): IProgress {
		/**
		 * Here is one ugly hack, so if you see it, look somewhere else, for example try to find
		 * some unicorn or rainbow... or both. Good luck.
		 */
		return $progress ?: SlimApp::$instance->injectOn(new self());
	}
}
