<?php
declare(strict_types=1);

namespace Edde\Tag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edde\Database\Entity\AbstractUuidEntity;

abstract class TagEntity extends AbstractUuidEntity {
	/**
	 * @ORM\Column(type="string", length=128)
	 * @var string
	 */
	public $code;
	/**
	 * @ORM\Column(type="string", length=128, name="label")
	 * @var string
	 */
	public $tag;
	/**
	 * @ORM\Column(type="string", length=128, nullable=true)
	 * @var string|null
	 */
	public $group;
	/**
	 * @ORM\Column(type="integer", options={"default": 0})
	 * @var int
	 */
	public $sort = 0;
}
