<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SdkCommand extends Command {
	use RpcHandlerIndexTrait;
	use SdkTrait;

	protected function configure() {
		$this->setName('sdk');
		$this->setDescription('Generates client-side SDK.');
		$this->addOption('package', 'p', InputOption::VALUE_REQUIRED, 'Exported package name');
		$this->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Sets output directory for SDK');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('SDK Generator started');
		$output->write("Found RPC handlers:\n\t- ");
		$output->writeln(implode("\n\t- ", $this->rpcHandlerIndex->getHandlers()));
		$this->sdk->generate($input->getOption('output'), $input->getOption('package'));
		$output->writeln('SDK Generator finished');
		return 0;
	}
}
