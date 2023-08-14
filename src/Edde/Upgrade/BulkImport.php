<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class BulkImport extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_bulk', [
				'comment' => 'Common bulk "header" for bulk items.',
			])
			->addColumn('created', 'datetime', ['comment' => 'Timestamp when a job was scheduled (basically also started).'])
			->addStringColumn('name', 512, ['comment' => 'Human readable name of this bulk import.'])
			->addColumn('status', 'integer', [
				'comment' => 'Overall bulk status',
				'default' => 0,
			])
			->addColumn('commit', 'boolean', [
				'comment' => 'When a human checks the bulk, commit means "it\'s done.".',
				'default' => false,
			])
			->addIdForeignColumn('user', 'main_user')
			->save();

		$this
			->createUuidTable('z_bulk_item', [
				'comment' => 'Item used for bulk changes/imports',
			])
			->addUuidForeignColumn('bulk', 'z_bulk')
			->addColumn('status', 'integer', [
				'comment' => 'Item status (one field): 0 - pending, 1 - success, 2 - error',
				'default' => 0,
			])
			->addTextColumn('request', [
				'comment' => 'JSON encoded request.',
				'null'    => true,
			])
			->addTextColumn('response', [
				'comment' => 'JSON encoded response (including error).',
				'null'    => true,
			])
			->addIdForeignColumn('user', 'main_user')
			->save();
	}
}
