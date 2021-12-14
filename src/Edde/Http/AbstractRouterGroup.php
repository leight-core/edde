<?php
declare(strict_types=1);

namespace Edde\Http;

abstract class AbstractRouterGroup implements IRouterGroup {
	use EndpointRegisterTrait;
}
