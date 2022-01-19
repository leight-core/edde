<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class TranslationUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_translation')
			->addStringColumn('locale', 32, ['comment' => 'Locale ID (cz-CZ, en-Gb, whatever...).'])
			->addColumn('key', 'text', [
				'comment' => 'Translation key.',
				'length'  => 4096 * 10,
			])
			->addStringColumn('hash', 128, ['comment' => 'Hash of a translation key.'])
			->addColumn('translation', 'text', [
				'comment' => 'The translation of the key.',
				'length'  => 4096 * 10,
			])
			->addIndex([
				'locale',
				'hash',
			], [
				'unique' => true,
				'name'   => 'z_translation_hash_unique',
			])
			->save();
	}
}
