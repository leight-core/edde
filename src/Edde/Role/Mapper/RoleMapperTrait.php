<?php
declare(strict_types=1);

namespace Edde\Role\Mapper;

trait RoleMapperTrait {
	/** @var RoleMapper */
	protected $roleMapper;

	/**
	 * @Inject
	 *
	 * @param RoleMapper $roleMapper
	 */
	public function setRoleMapper(RoleMapper $roleMapper): void {
		$this->roleMapper = $roleMapper;
	}
}
