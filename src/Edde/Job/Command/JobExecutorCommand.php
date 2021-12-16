<?php
declare(strict_types=1);

namespace Edde\Job\Command;

use Dibi\Exception;
use Edde\Job\JobExecutorTrait;
use Edde\Log\LoggerTrait;
use Edde\Log\TraceServiceTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\User\CurrentUserTrait;
use Nette\Utils\JsonException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class JobExecutorCommand extends Command {
	use JobExecutorTrait;
	use LoggerTrait;
	use TraceServiceTrait;
	use CurrentUserTrait;

	protected function configure() {
		$this->setName('job');
		$this->setDescription('Run the given job.');
		$this->addArgument('uuid', InputArgument::REQUIRED, 'Job uuid.');
		$this->addOption('trace', 't', InputOption::VALUE_REQUIRED, 'Parent trace ID for logging purposes.');
		$this->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'Optional user under which this command runs.');
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 *
	 * @throws Throwable
	 * @throws Exception
	 * @throws ItemException
	 * @throws SkipException
	 * @throws JsonException
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		try {
			$this->currentUser->select($input->getOption('user'));
			$this->traceService->setReference($input->getOption('trace'));
			$uuid = $input->getArgument('uuid');
			$this->logger->debug(sprintf('Starting [%s]; job uuid [%s]', self::class, $uuid), ['tags' => ['job']]);
			$this->jobExecutor->run($uuid);
			$this->logger->debug(sprintf('Success of [%s]', self::class), ['tags' => ['job']]);
		} catch (Throwable $exception) {
			$this->logger->debug(sprintf('Failure of [%s]', self::class), ['tags' => ['job']]);
			$this->logger->error($exception);
			throw $exception;
		}
		return self::SUCCESS;
	}
}
