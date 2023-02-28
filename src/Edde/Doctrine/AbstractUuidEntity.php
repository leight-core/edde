<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractUuidEntity extends AbstractEntity {
	/**
	 * @ORM\Id()
	 * @ORM\Column(type="string", length=36, unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class="Edde\Doctrine\UuidGenerator")
	 */
	public $id;
}
