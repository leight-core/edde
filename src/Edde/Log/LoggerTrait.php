<?php
declare(strict_types=1);

namespace Edde\Log;

use Psr\Log\LoggerInterface;

/**
 * If there is need to do some logging, use this logger, yaaay!
 */
trait LoggerTrait {
	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @Inject
	 *
	 * @param LoggerInterface $logger
	 */
	public function setLogger(LoggerInterface $logger): void {
		$this->logger = $logger;
	}
}
