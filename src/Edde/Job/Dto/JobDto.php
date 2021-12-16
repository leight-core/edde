<?php
declare(strict_types=1);

namespace Edde\Job\Dto;

use Edde\Bridge\User\UserDto;
use Edde\Dto\AbstractDto;

/**
 * @template TResult=void
 */
class JobDto extends AbstractDto {
	/**
	 * @var string
	 * @description job id
	 */
	public $id;
	/**
	 * @var int
	 * @description total number of items being processed in this job
	 */
	public $total;
	/**
	 * @var int
	 * @description number of successful items processed
	 */
	public $success;
	/**
	 * @var int
	 * @description ratio between success/failure
	 */
	public $ratio;
	/**
	 * @var string
	 * @description name of processing service
	 */
	public $service;
	/**
	 * @var int
	 * @description number of errors in the job
	 */
	public $error;
	/**
	 * @var int
	 * @description current number of processed items in the job
	 */
	public $count;
	/**
	 * @var float
	 * @description percentage progress
	 */
	public $progress;
	/**
	 * @var float
	 */
	public $performance;
	/**
	 * @var float
	 */
	public $runtime;
	/**
	 * @var string
	 * @description pre-formatted progress value
	 */
	public $formatted;
	/**
	 * @var int
	 * @description job status
	 */
	public $status;
	/**
	 * @var TResult|null
	 * @description result of the job (if any)
	 */
	public $result;
	/**
	 * @var bool
	 * @description if the job has logs, it's true; could be used as micro-optimization to prevent early query for job logs
	 */
	public $logs;
	/**
	 * @var mixed|null
	 * @description params a job has been executed with
	 */
	public $params;
	/**
	 * @var bool
	 * @description flag saying if the user reviewed (thus committed) result of the job
	 */
	public $commit;
	/**
	 * @var UserDto|void
	 */
	public $user;
	/**
	 * @var string
	 */
	public $created;
	/**
	 * @var string|null
	 */
	public $done;
}
