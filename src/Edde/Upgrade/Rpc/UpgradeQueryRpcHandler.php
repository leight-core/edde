<?php
declare(strict_types=1);

namespace Edde\Upgrade\Rpc;

use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Upgrade\Schema\UpgradeFilterSchema;
use Edde\Upgrade\Schema\UpgradeOrderBySchema;
use Edde\Upgrade\Schema\UpgradeQuerySchema;
use Edde\Upgrade\Schema\UpgradeSchema;

class UpgradeQueryRpcHandler extends AbstractRpcHandler {
	protected $requestSchema = UpgradeQuerySchema::class;
	protected $responseSchema = UpgradeSchema::class;
	protected $responseSchemaArray = true;
	protected $orderBySchema = UpgradeOrderBySchema::class;
	protected $filterSchema = UpgradeFilterSchema::class;
	protected $isQuery = true;

	public function handle(SmartDto $request) {
	}
}
