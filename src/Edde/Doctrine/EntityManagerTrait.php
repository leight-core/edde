<?php
declare(strict_types=1);

namespace Edde\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerTrait {
	/** @var EntityManagerInterface */
	protected $entityManager;

	/**
	 * @Inject
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function setEntityManager(EntityManagerInterface $entityManager): void {
		$this->entityManager = $entityManager;
	}
}
