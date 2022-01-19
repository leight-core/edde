<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class TranslationUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_translation')
			->addStringColumn('locale', 32, ['comment' => 'Locale ID (cz-CZ, en-Gb, whatever...).'])
			->addTextColumn('key', [
				'comment' => 'Translation key.',
			])
			->addStringColumn('hash', 128, ['comment' => 'Hash of a translation key.'])
			->addTextColumn('translation', [
				'comment' => 'The translation of the key.',
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
