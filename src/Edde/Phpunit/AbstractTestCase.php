<?php
declare(strict_types=1);

namespace Edde\Phpunit;

use Edde\Container\ContainerTrait;
use Edde\Slim\SlimApp;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase {
	use ContainerTrait;

	protected function setUp(): void {
		SlimApp::$instance->injectOn($this);
	}
}
