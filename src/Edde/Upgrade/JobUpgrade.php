<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class JobUpgrade extends CommonMigration {
	public function change(): void {
		$this->drop(
			'z_job_log',
			'z_job_lock',
			'z_job'
		);

		$this
			->createUuidTable('z_job', ['comment' => 'Table containing jobs running or being executed.'])
			->addStringColumn('service', 256, ['comment' => 'Service being called; must be accessible from DI container. Also it must implement IJobService interface.'])
			->addColumn('status', 'integer', [
				'limit'   => 1,
				'comment' => 'Status code of the job',
			])
			->addColumn('total', 'integer', [
				'default' => 0,
				'comment' => 'Number of items of the job (for example for imports).',
			])
			->addColumn('progress', 'double', [
				'default' => 0,
				'comment' => 'Percentage of progress (based on total and count).',
			])
			->addColumn('successCount', 'integer', [
				'default' => 0,
				'comment' => 'Number of successful items processed.',
			])
			->addColumn('errorCount', 'integer', [
				'default' => 0,
				'comment' => 'Number of failed items.',
			])
			->addColumn('skipCount', 'integer', [
				'default' => 0,
				'comment' => 'Number of skipped items.',
			])
			->addTextColumn('request', [
				'comment' => 'JSON encoded parameter DTO.',
				'null'    => true,
			])
			->addStringColumn('requestSchema', 256, ['null' => true])
			->addTextColumn('response', [
				'comment' => 'JSON encoded response DTO.',
				'null'    => true,
			])
			->addStringColumn('responseSchema', 256, ['null' => true])
			->addColumn('started', 'datetime', ['comment' => 'Timestamp when a job was scheduled (basically also started).'])
			->addColumn('finished', 'datetime', [
				'comment' => 'Timestamp when a job was done (finished); should **not** be used as value for job status check!',
				'null'    => true,
			])
			->addStringColumn('user_id', 36)
			->save();

		$this
			->createUuidTable('z_job_lock', ['comment' => 'Job locks to make them run in order.'])
			->addUuidForeignColumn('job', 'z_job', ['comment' => 'Related job to which the lock belongs'])
			->addStringColumn('name', 128, ['comment' => 'Lock name'])
			->addColumn('active', 'boolean', ['comment' => 'Is the lock active?'])
			->addColumn('stamp', 'double', ['comment' => 'When a lock has been created (microtime).'])
			->save();

		$this
			->createUuidTable('z_job_log', ['comment' => 'When something during a job occurs, it\'s recorded here.'])
			->addUuidForeignColumn('job', 'z_job', ['comment' => 'Reference to parent job of this log item.'])
			->addStringColumn('type', 128, [
				'null'    => true,
				'comment' => 'Log type could be used on a client side to determine error type (for example duplicate key, missing values, ...) to render detailed info of the error. Or to understand the error at all.',
			])
			->addColumn('level', 'integer', [
				'limit'   => 2,
				'comment' => 'Log level (the weight - for example 0 - notice, 4 - error).',
			])
			->addTextColumn('item', [
				'comment' => 'JSON encoded source (processed item of a job).',
				'null'    => true,
			])
			->addStringColumn('reference', 512, [
				'comment' => 'Optional reference to the item of log (for example foreign key id).',
				'null'    => true,
			])
			->addTextColumn('message', [
				'comment' => 'Content of the message',
			])
			->addColumn('stamp', 'datetime', ['comment' => 'When this log item occurred (microtime).'])
			->save();
	}
}
