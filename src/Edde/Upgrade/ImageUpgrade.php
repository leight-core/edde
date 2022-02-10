<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class ImageUpgrade extends CommonMigration {
	public function change() {
		$this
			->table('z_image')
			->addStringColumn('gallery', 128, [
				'comment' => 'Group images into a gallery.',
				'null'    => true,
			])
			->addUuidForeignColumn('user', 'z_user', [
				'comment' => 'Image owner.',
			])
			->addUuidForeignColumn('preview', 'z_file', [
				'comment' => 'Preview version of an image.',
			])
			->addUuidForeignColumn('original', 'z_file', [
				'comment' => 'Original version of an image.',
			])
			->addColumn('stamp', 'datetime', [
				'comment' => 'When an image has been created.',
			])
			->save();
	}
}
