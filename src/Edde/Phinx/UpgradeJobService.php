<?php
declare(strict_types=1);

namespace Edde\Phinx;

use Edde\Job\AbstractJobService;
use Edde\Job\IJob;
use Edde\Progress\Dto\ItemDto;
use Edde\Progress\IProgress;
use function sprintf;
use function usleep;

class UpgradeJobService extends AbstractJobService {
	use UpgradeManagerTrait;

	protected function handle(IJob $job) {
		$progress = $job->getProgress();
		$progress->onStart($this->upgradeManager->getPendingCount());
		foreach ($this->upgradeManager->getPending() as $abstractMigration) {
			$item = ItemDto::create(['index' => sprintf('%s-%s', $abstractMigration->getVersion(), $abstractMigration->getName())]);
			$progress->log(IProgress::LOG_INFO, sprintf('Starting migration [%s] version [%s].', $abstractMigration->getName(), $abstractMigration->getVersion()), $item);
			/**
			 * Here is missing try-catch for migration: this is intentional as next migration cannot be run over
			 * the failed one; if migration expects failure, it should handle it itself.
			 */
			$this->upgradeManager->execute($abstractMigration);
			$progress->log(IProgress::LOG_INFO, sprintf('Migration [%s] successful.', $abstractMigration->getVersion()), $item);
			/**
			 * Take a breath for a while.
			 */
			usleep(500000);
			$progress->onProgress($item);
		}
	}
}
