<?php
declare(strict_types=1);

namespace Edde\Database\Entity;

abstract class AbstractUuidEntity extends AbstractEntity {
	/**
	 * @var string
	 */
	public $id;
}
