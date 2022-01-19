<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class FileUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_file', ['comment' => 'Support for saving files into the database'])
			->addStringColumn('path', 256, ['comment' => 'A "virtual" path of the file; it is unique with filename.'])
			->addStringColumn('name', 256, ['comment' => 'Yes, it is a filename.'])
			->addStringColumn('mime', 256, [
				'comment' => 'Mime type of the file.',
				'default' => 'application/octet-stream',
			])
			->addColumn('size', 'biginteger', [
				'comment' => 'Mime type of the file.',
			])
			->addColumn('created', 'datetime', [
				'comment' => 'When a file was created.',
			])
			->addColumn('updated', 'datetime', [
				'comment' => 'When a file is overridden, it got updated timestamp.',
				'null'    => true,
			])
			->addColumn('ttl', 'double', [
				'comment' => 'Optional TTL of the file; common work with files could execute cleanup of "dead" files to prevent database bloating.',
				'null'    => true,
			])
			->addStringColumn('native', 2048, ['comment' => 'Where the file lives one the filesystem.'])
			->addStringColumn('native_hash', 128, ['comment' => 'Hash of native for unique index.'])
			->addIndex(['native_hash'], [
				'unique' => true,
				'name'   => 'z_file_native_unique',
			])
			->addUuidForeignColumn('user', 'z_user', [
				'comment' => 'An optional user who created the file.',
				'null'    => true,
			], [
				'delete' => 'SET_NULL',
			])
			->addIndex([
				'path',
				'name',
			], [
				'name'   => 'z_file_name_unique',
				'unique' => true,
			])
			->save();
	}
}
