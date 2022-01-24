<?php
declare(strict_types=1);

namespace Edde\Api\Root\Log\Endpoint;

use Dibi\Exception;
use Edde\Log\Repository\LogRepositoryTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

/**
 * @description Clear all logs (regardless of time).
 */
class DropLogsEndpoint extends AbstractMutationEndpoint {
	use LogRepositoryTrait;

	/**
	 *
	 * @throws Exception
	 */
	public function delete(): void {
		$this->logRepository->truncate();
	}
}
