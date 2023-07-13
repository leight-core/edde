<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\IRpcHandler;
use Edde\Schema\SchemaLoaderTrait;
use Edde\Sdk\AbstractExport;

abstract class AbstractRpcExport extends AbstractExport {
	use SchemaLoaderTrait;

	/**
	 * @var IRpcHandler
	 */
	protected $handler;

	public function withHandler(IRpcHandler $handler): self {
		$this->handler = $handler;
		return $this;
	}
}
