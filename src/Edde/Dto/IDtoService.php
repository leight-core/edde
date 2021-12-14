<?php
declare(strict_types=1);

namespace Edde\Dto;

interface IDtoService {
	/**
	 * @param string     $class
	 * @param array|null $source
	 *
	 * @return mixed
	 *
	 * @see self::fromObject()
	 */
	public function fromArray(string $class, ?array $source);

	/**
	 * @param string $class
	 * @param array  $sources
	 *
	 * @return array
	 */
	public function fromArrays(string $class, array $sources): array;

	/**
	 * @param string      $class
	 * @param object|null $source
	 *
	 * @return mixed
	 */
	public function fromObject(string $class, ?object $source);

	/**
	 * @param string   $class
	 * @param object[] $objects
	 *
	 * @return array
	 */
	public function fromObjects(string $class, array $objects): array;
}
