<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class LogUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_log')
			->addStringColumn('type', 24, ['comment' => 'Log type (info, warning, ...).'])
			->addTextColumn('log', [
				'comment' => 'Some space for the log record.',
			])
			->addTextColumn('stack', [
				'comment' => 'Optional log trace (call stack).',
				'null'    => true,
			])
			->addColumn('stamp', 'datetime', [
				'comment' => 'When a log record has been created.',
			])
			->addStringColumn('trace', 128, ['comment' => 'Trace ID for tracing logs bound together.'])
			->addStringColumn('reference', 128, [
				'comment' => 'Soft reference to a parent log (intentionally not a foreign key to simplify logging itself).',
				'null'    => true,
			])
			->addColumn('microtime', 'double', ['comment' => 'Logs have a timestamp, microtime tracks time in a "request" to make right order of log items.'])
			->addTextColumn('context', [
				'null'    => true,
				'comment' => 'JSON encoded log context.',
			])
			->addUuidForeignColumn('user', 'z_user', [
				'comment' => 'An optional user who created log item.',
				'null'    => true,
			], [
				'delete' => 'SET_NULL',
			])
			->save();

		$this
			->createUuidTable('z_log_tag', ['comment' => 'Log item -> tag relation; useful for searching something.'])
			->addUuidForeignColumn('log', 'z_log', ['comment' => 'Reference to a log record.'])
			->addUuidForeignColumn('tag', 'z_tag', ['comment' => 'Reference to a tag.'])
			->save();
	}
}
