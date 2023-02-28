<?php
declare(strict_types=1);

namespace Edde\Translation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edde\Doctrine\AbstractUuidEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="z_translation")
 */
class TranslationEntity extends AbstractUuidEntity {
	/**
	 * @ORM\Column(type="string")
	 */
	public $locale;
	/**
	 * @ORM\Column(type="string")
	 */
	public $key;
	/**
	 * @ORM\Column(type="string")
	 */
	public $hash;
	/**
	 * @ORM\Column(type="string")
	 */
	public $translation;
}
