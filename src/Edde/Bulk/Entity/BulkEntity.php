<?php
declare(strict_types=1);

namespace Edde\Bulk\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Edde\Doctrine\AbstractUuidEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="z_bulk")
 */
class BulkEntity extends AbstractUuidEntity {
	/**
	 * @ORM\Column(type="string", length=512)
	 * @var string
	 */
	public $name;
	/**
	 * @ORM\Column(type="string", length=512)
	 * @var string
	 */
	public $service;
	/**
	 * @ORM\Column(type="integer")
	 * @var integer
	 */
	public $status;
	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	public $commit;
	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	public $created;
	/**
	 * @ORM\Column(type="string", name="user_id", length=36)
	 * @var string
	 */
	public $userId;
	/**
	 * @ORM\OneToMany(targetEntity="Edde\Bulk\Entity\BulkItemEntity", fetch="EXTRA_LAZY", mappedBy="bulk")
	 *
	 * @var Collection<BulkItemEntity>
	 */
	public $bulkItems;

	public function __construct() {
		$this->bulkItems = new ArrayCollection();
	}
}
