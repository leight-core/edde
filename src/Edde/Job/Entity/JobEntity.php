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
}
