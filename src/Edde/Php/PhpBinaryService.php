<?php
declare(strict_types=1);

namespace Edde\Php;

use Edde\Log\LoggerTrait;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\PhpExecutableFinder;

class PhpBinaryService implements IPhpBinaryService {
	use LoggerTrait;

	public function find(?string $fallback = null): string {
		$this->logger->debug(sprintf('Searching for PHP bin (php-cli) in [%s].', PHP_BINDIR));
		return str_replace('cgi', 'cli', (new ExecutableFinder())->find('php-cli', null, [PHP_BINDIR]) ??
			(new PhpExecutableFinder())->find() ??
			$fallback ??
			PHP_BINDIR . '/php-cli.exe');
	}
}
