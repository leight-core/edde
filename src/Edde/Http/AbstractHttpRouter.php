<?php
declare(strict_types=1);

namespace Edde\Http;

abstract class AbstractHttpRouter implements IHttpRouter {
	use EndpointRegisterTrait;
}
