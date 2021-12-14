<?php
declare(strict_types=1);

namespace Edde\Log\Repository;

use Edde\Repository\AbstractRepository;
use Edde\Tag\Repository\TagRepositoryTrait;

class LogTagRepository extends AbstractRepository {
	use TagRepositoryTrait;

	public function sync($logId, array $tags) {
		$this->syncWith('log_id', 'tag_id', $logId, array_map(function ($code) {
			return $this->tagRepository->ensure($code, 'log')->id;
		}, $tags));
	}

	public function findTagByLog(string $logId) {
		return $this->storage
			->table('z_tag')
			->select()
			->leftJoin('z_log_tag as zlt', 'z_tag.id', '=', 'zlt.tag_id')
			->where('zlt.log_id', $logId)
			->execute();
	}
}
