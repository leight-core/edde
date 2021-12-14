<?php
declare(strict_types=1);

namespace Edde\Log\Mapper;

/**
 * Default log mapper from database row.
 */
trait LogMapperTrait {
	/** @var LogMapper */
	protected $logMapper;

	/**
	 * @Inject
	 *
	 * @param LogMapper $logMapper
	 */
	public function setLogMapper(LogMapper $logMapper): void {
		$this->logMapper = $logMapper;
	}
}
