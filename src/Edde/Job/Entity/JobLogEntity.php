<?php
declare(strict_types=1);

namespace Edde\Job\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edde\Doctrine\AbstractUuidEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="z_job_log")
 */
class JobLogEntity extends AbstractUuidEntity {
	/**
	 * @ORM\Column(type="string", length=36)
	 * @var string
	 */
	public $jobId;
	/**
	 * @ORM\Column(type="string", length=128, nullable=true)
	 * @var string
	 */
	public $type;
	/**
	 * @ORM\Column(type="integer")
	 * @var string
	 */
	public $level;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string
	 */
	public $item;
	/**
	 * @ORM\Column(type="string", length=512, nullable=true)
	 * @var string
	 */
	public $reference;
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	public $message;
	/**
	 * @ORM\Column(type="datetime")
	 * @var string
	 */
	public $stamp;
}
