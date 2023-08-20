<?php
declare(strict_types=1);

namespace Edde\Job\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Edde\Database\Entity\AbstractUuidEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="z_job_lock")
 */
class JobLockEntity extends AbstractUuidEntity {
	/**
	 * @ORM\Column(type="string", length=36, name="job_id")
	 * @var string
	 */
	public $jobId;
	/**
	 * @ORM\Column(type="string", length=128)
	 * @var string
	 */
	public $name;
	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	public $active;
	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	public $stamp;
}
