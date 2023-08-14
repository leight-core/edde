<?php
declare(strict_types=1);

namespace Edde\Bulk\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edde\Doctrine\AbstractUuidEntity;

class BulkItemEntity extends AbstractUuidEntity {
	/**
	 * @ORM\Column(type="integer")
	 * @var integer
	 */
	public $status;
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	public $request;
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	public $response;
	/**
	 * @ORM\Column(type="string", length=36)
	 * @var string
	 */
	public $bulkId;
	/**
	 * @ORM\Column(type="string", length=36)
	 * @var string
	 */
	public $userId;
	/**
	 * @ORM\ManyToOne(targetEntity="Edde\Bulk\Entity\BulkEntity", inversedBy="bulkItems")
	 * @var BulkEntity
	 */
	public $bulk;
}