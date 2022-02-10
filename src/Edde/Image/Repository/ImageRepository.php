<?php
declare(strict_types=1);

namespace Edde\Image\Repository;

use DateTime;
use Edde\Image\Dto\CreateDto;
use Edde\Repository\AbstractRepository;
use Edde\Repository\IRepository;

class ImageRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['stamp' => IRepository::ORDER_DESC]);
	}

	public function create(CreateDto $createDto) {
		return $this->insert([
			'gallery'     => $createDto->gallery,
			'preview_id'  => $createDto->previewId,
			'original_id' => $createDto->originalId,
			'stamp'       => new DateTime(),
			'user_id'     => $createDto->userId,
		]);
	}
}
