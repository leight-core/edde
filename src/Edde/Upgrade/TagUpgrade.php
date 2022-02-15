<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class TagUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_tag', ['comment' => 'Simple table of tags generally usable anywhere.'])
			->addStringColumn('code', 128, ['comment' => 'Short tag code. Shall be lower-case.'])
			->addStringColumn('label', 128, ['comment' => 'Tag label; shall be translated.'])
			->addStringColumn('group', 128, [
				'null'    => true,
				'comment' => 'Tags could be (optionally) grouped for usage in different parts of the app.',
			])
			->addColumn('sort', 'integer', [
				'comment' => 'An (optional) stable way, how to sort tags.',
				'default' => 0,
			])
			->addUniqueIndex([
				'code',
				'group',
			], 'code')
			->save();
	}
}
