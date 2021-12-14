<?php
declare(strict_types=1);

namespace Edde\File\Repository;

use ClanCats\Hydrahon\Query\Sql\Select;
use DateTime;
use Dibi\Row;
use Edde\File\Dto\EnsureDto;
use Edde\File\Dto\FileFilterDto;
use Edde\Log\LoggerTrait;
use Edde\Query\Dto\Query;
use Edde\Repository\AbstractRepository;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Uuid\UuidServiceTrait;
use function hash;
use function microtime;

class FileRepository extends AbstractRepository {
	use LoggerTrait;
	use UuidServiceTrait;

	public function __construct() {
		parent::__construct([
			'created' => false,
			'path'    => true,
			'name'    => true,
		], [
			'z_file_name_unique',
			'z_file_native_unique',
		]);

	}

	public function toQuery(Query $query): Select {
		$select = $this->select();

		/** @var $filter FileFilterDto */
		$filter = $query->filter;
		$filter->fulltext && $this->fulltext($select, [
			'id',
			'path',
			'name',
			'mime',
		], $filter->fulltext);
		$filter->userIds && $select->where('user_id', 'in', $filter->userIds);
		$filter->paths && $select->where('path', 'in', $filter->paths);
		$filter->path && $this->fulltext($select, ['path'], $filter->path);
		$filter->mimes && $select->where('mimes', 'in', $filter->mimes);

		$this->toOrderBy($query->orderBy, $select);

		return $select;
	}

	public function findByPath(string $path, string $name) {
		return $this->select()->where('path', $path)->where('name', $name)->execute()->fetch();
	}

	public function findByNative(string $native): ?Row {
		return $this->select()->where('native', $native)->execute()->fetch();
	}

	public function deleteByNative(string $native) {
		$this->table()->delete()->where('native', $native)->execute();
	}

	public function ensure(EnsureDto $ensureDto) {
		try {
			return $this->insert([
				'path'        => $ensureDto->path,
				'name'        => $ensureDto->name,
				'mime'        => $ensureDto->mime,
				'ttl'         => $ensureDto->ttl ? microtime(true) + $ensureDto->ttl : null,
				'size'        => $ensureDto->size,
				'native'      => $ensureDto->native,
				'created'     => new DateTime(),
				'native_hash' => hash('sha256', $ensureDto->native),
				'user_id'     => $ensureDto->userId,
			]);
		} catch (DuplicateEntryException $exception) {
			return $this->change([
				'id'   => $this->findByPath($ensureDto->path, $ensureDto->name)->id,
				'path' => $ensureDto->path,
				'name' => $ensureDto->name,
				'mime' => $ensureDto->mime,
				'ttl'  => $ensureDto->ttl ? microtime(true) + $ensureDto->ttl : null,
				/**
				 * native is missing intentionally; if there is native file with a content, this
				 * could mess everything up; ensure should ensure that native file eventually exists
				 * with expected content; call site is responsible for maintaining the resulting
				 * file
				 */
			]);
		}
	}
}
