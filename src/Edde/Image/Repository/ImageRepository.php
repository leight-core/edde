<?php
declare(strict_types=1);

namespace Edde\Image\Repository;

use ClanCats\Hydrahon\Query\Sql\Select;
use DateTime;
use Edde\Image\Dto\CreateDto;
use Edde\Image\Dto\ImageFilterDto;
use Edde\Query\Dto\Query;
use Edde\Repository\AbstractRepository;
use Edde\Repository\IRepository;

class ImageRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['stamp' => IRepository::ORDER_DESC]);
	}

	public function toQuery(Query $query): Select {
		$select = parent::toQuery($query);

		/** @var $filter ImageFilterDto */
		$filter = $query->filter;

		$this->toOrderBy($query->orderBy, $select);

		return $select;
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
