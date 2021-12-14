<?php
declare(strict_types=1);

namespace Edde\Phinx;

use Phinx\Migration\AbstractMigration;
use Phinx\Migration\Manager;
use function in_array;
use function ksort;

class UpgradeManager {
	/** @var Manager */
	protected $manager;

	/**
	 * @Inject
	 *
	 * @param Manager $manager
	 */
	public function setManager(Manager $manager) {
		$this->manager = $manager;
	}

	public function upgrade() {
		$this->manager->migrate($this->getEnvironment());
	}

	/**
	 * @return AbstractMigration[]
	 */
	public function migrations(): array {
		return $this->manager->getMigrations($this->getEnvironment());
	}

	public function print() {
		$this->manager->printStatus($this->getEnvironment());
	}

	public function getEnvironment(): string {
		return $this->manager->getConfig()->getDefaultEnvironment();
	}

	public function isActive(AbstractMigration $migration): bool {
		$environment = $this->manager->getEnvironment($this->getEnvironment());
		return in_array($migration->getVersion(), $environment->getVersions());
	}

	public function getPendingCount(): int {
		return count($this->getPending());
	}

	/**
	 * @return AbstractMigration[]
	 */
	public function getPending(): array {
		$pending = [];
		foreach ($this->migrations() as $k => $migration) {
			!$this->isActive($migration) && $pending[$k] = $migration;
		}
		ksort($pending);
		return $pending;
	}

	public function execute(AbstractMigration $migration) {
		$this->manager->getEnvironment($this->getEnvironment())->executeMigration($migration);
	}
}
