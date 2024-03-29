<?php
declare(strict_types=1);

namespace Edde\Phinx;

use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Progress\IProgress;
use function sprintf;
use function usleep;

class UpgradeAsyncService extends AbstractAsyncService {
	use UpgradeManagerTrait;

	protected function handle(SmartDto $job, IProgress $progress, ?SmartDto $request) {
		$progress->onStart($this->upgradeManager->getPendingCount());
		foreach ($this->upgradeManager->getPending() as $abstractMigration) {
			$progress->onCurrent(['index' => sprintf('%s-%s', $abstractMigration->getVersion(), $abstractMigration->getName())]);
			$progress->log(IProgress::LOG_INFO, sprintf('Starting migration [%s] version [%s].', $abstractMigration->getName(), $abstractMigration->getVersion()));
			/**
			 * Here is missing try-catch for migration: this is intentional as next migration cannot be run over
			 * the failed one; if migration expects failure, it should handle it itself.
			 */
			$this->upgradeManager->execute($abstractMigration);
			$progress->log(IProgress::LOG_INFO, sprintf('Migration [%s] successful.', $abstractMigration->getVersion()));
			/**
			 * Take a breath for a while.
			 */
			usleep(500 * 1000);
			$progress->onProgress();
		}
	}
}
