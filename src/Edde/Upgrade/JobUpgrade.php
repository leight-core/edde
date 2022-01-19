<?php
declare(strict_types=1);

namespace Edde\Upgrade;

use Edde\Phinx\CommonMigration;

class JobUpgrade extends CommonMigration {
	public function change(): void {
		$this
			->createUuidTable('z_job', ['comment' => 'Table containing jobs running or being executed.'])
			->addStringColumn('service', 256, ['comment' => 'Service being called; must be accessible from DI container. Also it must implement IJobService interface.'])
			->addColumn('params', 'text', [
				'comment' => 'JSON encoded parameter DTO.',
				'null'    => true,
				'length'  => 4096 * 10,
			])
			->addColumn('result', 'text', [
				'comment' => 'JSON encoded result DTO.',
				'null'    => true,
				'length'  => 4096 * 10,
			])
			->addColumn('total', 'integer', [
				'default' => 0,
				'comment' => 'Number of items of the job (for example for imports).',
			])
			->addColumn('success', 'integer', [
				'default' => 0,
				'comment' => 'Number of successful items processed.',
			])
			->addColumn('error', 'integer', [
				'default' => 0,
				'comment' => 'Number of failed items.',
			])
			->addColumn('progress', 'double', [
				'default' => 0,
				'comment' => 'Percentage of progress (based on total and count).',
			])
			->addColumn('runtime', 'double', [
				'default' => 0,
				'comment' => 'How log the job have been running.',
			])
			->addColumn('performance', 'double', [
				'default' => 0,
				'comment' => 'Runtime per item (computed from runtime and count).',
			])
			->addColumn('status', 'integer', [
				'limit'   => 1,
				'comment' => 'Status code of the job',
			])
			->addColumn('created', 'datetime', ['comment' => 'Timestamp when a job was scheduled (basically also started).'])
			->addColumn('done', 'datetime', [
				'comment' => 'Timestamp when a job was done (finished); should **not** be used as value for job status check!',
				'null'    => true,
			])
			->addUuidForeignColumn('user', 'z_user', [
				'comment' => 'An optional user who created this job.',
				'null'    => true,
			])
			->addColumn('commit', 'boolean', [
				'comment' => 'When a Human started a job, it could be marked as committed which means a Human made a revision of job state.',
				'default' => false,
			])
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
			->addColumn('item', 'text', [
				'comment' => 'JSON encoded source (processed item of a job).',
				'null'    => true,
				'length'  => 4096 * 10,
			])
			->addStringColumn('reference', 512, [
				'comment' => 'Optional reference to the item of log (for example foreign key id).',
				'null'    => true,
			])
			->addColumn('message', 'text', [
				'comment' => 'Content of the message',
				'length'  => 4096 * 10,
			])
			->addColumn('stamp', 'double', ['comment' => 'When this log item occurred (microtime).'])
			->save();
	}
}
