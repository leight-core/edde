<?php
declare(strict_types=1);

namespace Edde\Translation\Repository;

use ClanCats\Hydrahon\Query\Sql\Exception;
use Edde\Repository\AbstractRepository;
use Edde\Repository\Exception\DuplicateEntryException;
use Edde\Translation\Dto\Create\CreateDto;
use Edde\Translation\Dto\Ensure\EnsureDto;
use Throwable;
use function sha1;

class TranslationRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['key' => false], [
			'z_translation_hash_unique',
		]);
	}

	/**
	 * @param string $locale
	 * @param string $key
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function fetchByKey(string $locale, string $key) {
		return $this->select()->where('locale', $locale)->where('hash', $this->key($key))->execute()->fetch();
	}

	/**
	 * @param CreateDto $createDto
	 *
	 * @return mixed
	 *
	 * @throws Throwable
	 */
	public function create(CreateDto $createDto) {
		return $this->insert([
			'locale'      => $createDto->translation->language,
			'key'         => $createDto->translation->label,
			'hash'        => $this->key($createDto->translation->label),
			'translation' => $createDto->translation->translation,
		]);
	}

	public function ensure(EnsureDto $ensureDto) {
		try {
			return $this->insert([
				'locale'      => $ensureDto->translation->language,
				'key'         => $ensureDto->translation->label,
				'hash'        => $this->key($ensureDto->translation->label),
				'translation' => $ensureDto->translation->translation,
			]);
		} catch (DuplicateEntryException $exception) {
			return $this->change([
				'id'          => $this->fetchByKey($ensureDto->translation->language, $ensureDto->translation->label)->id,
				'translation' => $ensureDto->translation->translation,
			]);
		}
	}

	public function languages() {
		return $this->native("SELECT DISTINCT locale FROM %n", $this->table);
	}

	public function toLanguages(): array {
		return iterator_to_array($this->languages());
	}

	protected function key(string $key): string {
		return sha1($key);
	}
}
