<?php
declare(strict_types=1);

namespace Edde\Job\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edde\Doctrine\AbstractUuidEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="z_job")
 */
class JobEntity extends AbstractUuidEntity {
	/**
	 * @ORM\Column(type="string", length=256)
	 * @var string
	 */
	public $service;
	/**
	 * @ORM\Column(type="integer")
	 * @var string
	 */
	public $status;
	/**
	 * @ORM\Column(type="integer")
	 * @var string
	 */
	public $total;
	/**
	 * @ORM\Column(type="float")
	 * @var string
	 */
	public $progress;
	/**
	 * @ORM\Column(type="integer")
	 * @var string
	 */
	public $successCount;
	/**
	 * @ORM\Column(type="integer")
	 * @var string
	 */
	public $errorCount;
	/**
	 * @ORM\Column(type="integer")
	 * @var string
	 */
	public $skipCount;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string
	 */
	public $request;
	/**
	 * @ORM\Column(type="string", length=256, nullable=true)
	 * @var string
	 */
	public $requestSchema;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string
	 */
	public $response;
	/**
	 * @ORM\Column(type="string", length=256, nullable=true)
	 * @var string
	 */
	public $responseSchema;
	/**
	 * @ORM\Column(type="datetime")
	 * @var string
	 */
	public $started;
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var string
	 */
	public $finished;
	/**
	 * @ORM\Column(type="string", length=36)
	 * @var string
	 */
	public $userId;
}
