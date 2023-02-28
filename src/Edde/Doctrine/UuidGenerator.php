<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Ramsey\Uuid\Uuid;

class UuidGenerator extends AbstractIdGenerator {
	public function generateId(EntityManagerInterface $em, $entity) {
		return Uuid::uuid4()->toString();
	}
}
