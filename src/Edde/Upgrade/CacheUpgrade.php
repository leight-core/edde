<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class CacheUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_cache', ['comment' => 'Database cache support (the last resort).'])
			->addStringColumn('key', 128, ['comment' => 'An unique cache key.'])
			->addColumn('value', 'binary', [
				'comment' => 'Cached value; could be null. Could be basically anything, value, json encoded, whatever an implementation needs. The column limit (length) should be checked!',
				'length'  => 16384 * 32,
				'null'    => true,
			])
			->addStringColumn('hash', 512, [
				'comment' => 'Optional hash of the value to ensure all data is persisted (for example when an overflow occurs, hash could catch this).',
				'null'    => true,
			])
			->addColumn('ttl', 'datetime', [
				'comment' => 'Optional timestamp after which cached item expires (so even a cache item exists in the table, cache miss may happen if ttl is over).',
				'null'    => true,
			])
			->addIndex('key', [
				'unique' => true,
				'name'   => 'z_cache_key_unique',
			])
			->save();
	}
}
