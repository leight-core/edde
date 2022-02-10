<?php
declare(strict_types=1);

namespace Edde\Image\Mapper;

trait ImageMapperTrait {
	/**
	 * @var ImageMapper
	 */
	protected $imageMapper;

	/**
	 * @Inject
	 *
	 * @param ImageMapper $imageMapper
	 */
	public function setImageMapper(ImageMapper $imageMapper): void {
		$this->imageMapper = $imageMapper;
	}
}
