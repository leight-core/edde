<?php
declare(strict_types=1);

namespace Edde\Reflection;

/**
 * Service used to do create reflection DTOs over classes and base types.
 */
trait ReflectionServiceTrait {
	/** @var ReflectionService */
	protected $reflectionService;

	/**
	 * @Inject
	 *
	 * @param ReflectionService $reflectionService
	 */
	public function setReflectionService(ReflectionService $reflectionService): void {
		$this->reflectionService = $reflectionService;
	}
}
