<?php
declare(strict_types=1);

namespace Edde\Discovery\Dto;

use Edde\Dto\AbstractDto;

/**
 * Do not change properties of this object as it's used silently on
 * various places which could (probably will) brake SDK generator of the app
 * and discovery itself.
 */
class DiscoveryItemDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 * @description just an url part of an endpoint (like /foo/bar)
	 */
	public $url;
	/**
	 * @var string
	 * @description full link to an endpoint (like http://localhost:9090/foo/bar)
	 */
	public $link;
	/**
	 * @var string[]
	 */
	public $params;
}
