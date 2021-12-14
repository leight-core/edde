<?php
declare(strict_types=1);

namespace Edde\Phpunit;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase {
	protected function setUp(): void {
		(require __DIR__ . '/../../../../bootstrap.php')->getContainer()->injectOn($this);
	}
}
