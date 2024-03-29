<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class BulkImportUpgrade extends CommonMigration {
	public function change(): void {
		$this->drop(
			'z_bulk_item',
			'z_bulk'
		);
		$this
			->createUuidTable('z_bulk', [
				'comment' => 'Common bulk "header" for bulk items.',
			])
			->addColumn('created', 'datetime')
			->addStringColumn('name', 512, ['comment' => 'Human readable name of this bulk import.'])
			->addColumn('status', 'integer', [
				'comment' => 'Overall bulk status',
				'default' => 0,
			])
			->addColumn('commit', 'boolean', [
				'comment' => 'When a human checks the bulk, commit means "it\'s done.".',
				'default' => false,
			])
			->addStringColumn('user_id', 36)
			->save();

		$this
			->createUuidTable('z_bulk_item', [
				'comment' => 'Item used for bulk changes/imports',
			])
			->addUuidForeignColumn('bulk', 'z_bulk')
			->addStringColumn('service', 512, ['comment' => 'Service being called.'])
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
			->addColumn('created', 'datetime')
			->addStringColumn('user_id', 36)
			->save();
	}
}
