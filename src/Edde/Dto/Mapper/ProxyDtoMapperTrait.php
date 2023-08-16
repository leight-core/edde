<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

trait ProxyDtoMapperTrait {
	/**
	 * @var ProxyDtoMapper
	 */
	protected $proxyDtoMapper;

	/**
	 * @Inject
	 *
	 * @param ProxyDtoMapper $proxyDtoMapper
	 *
	 * @return void
	 */
	public function setProxyDtoMapper(ProxyDtoMapper $proxyDtoMapper): void {
		$this->proxyDtoMapper = $proxyDtoMapper;
	}
}
