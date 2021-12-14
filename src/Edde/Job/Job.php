<?php
declare(strict_types=1);

namespace Edde\Job;

use Edde\Progress\IProgress;

class Job implements IJob {
	/** @var string */
	protected $id;
	/** @var mixed */
	protected $params;
	/** @var IProgress */
	protected $progress;
	/** @var string|null */
	protected $userId;

	public function __construct(string $id, $params, IProgress $progress, string $userId = null) {
		$this->id = $id;
		$this->params = $params;
		$this->progress = $progress;
		$this->userId = $userId;
	}

	public function getId(): string {
		return $this->id;
	}

	public function getParams() {
		return $this->params;
	}

	public function getProgress(): IProgress {
		return $this->progress;
	}

	public function getUserId(): ?string {
		return $this->userId;
	}
}
