<?php

namespace NF\Commands;

use NF\CompileBladeString\Facade\BladeCompiler;
use NF\Facades\Storage;
use NF\Facades\BindingGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListTaxonomyCommand extends Command
{
	protected function configure()
	{
		$this->setName('taxonomy:list')
			->setDescription('Return a list of available taxonomies')
			->setHelp('This command allows you get a list of available taxonomies');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$path = '/App' . DIRECTORY_SEPARATOR . 'Taxonomies';
		$files = Storage::listContents($path, true);

		$output->writeln("+----------------------------+");
		$output->writeln("| name                       |");
		$output->writeln("+----------------------------+");

		foreach ($files as $file) {
			$isNormalFile = strpos($file['basename'], '.');

			if ($isNormalFile === false || $isNormalFile == 0) continue;

			$output->writeln("| {$file['filename']} |");
		}

		$output->writeln("+----------------------------+");
		//var_dump($files);

	}
}