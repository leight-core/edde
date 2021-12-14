<?php
declare(strict_types=1);

namespace Edde\Log\Mapper;

use Edde\Mapper\AbstractMapper;
use Marsh\User\Mapper\UserMapperTrait;
use Marsh\User\UserRepositoryTrait;

/**
 * @Injectable(lazy=true)
 */
class UserActivityMapper extends AbstractMapper {
	use UserMapperTrait;
	use UserRepositoryTrait;

	public function item($item, array $params = []) {
		return [
			'user'  => $this->userMapper->item($this->userRepository->find($item->user_id), $params),
			'count' => $item->items,
		];
	}

	public function map(iterable $source): array {
		$mapped = parent::map($source);
		$max = max(array_map(function ($item) {
			return $item['count'];
		}, $mapped));
		return array_map(function ($item) use ($max) {
			$item['score'] = 100 * $item['count'] / $max;
			return $item;
		}, $mapped);
	}
}
