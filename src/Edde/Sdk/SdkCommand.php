<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SdkCommand extends Command {
	protected function configure() {
		$this->setName('sdk');
		$this->setDescription('Generates client-side SDK.');
		$this->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Sets output directory for SDK');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('SDK Generator started');
		$output->writeln('SDK Generator finished');
	}
}
