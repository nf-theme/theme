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

class RemoveTaxonomyCommand extends Command
{
	protected function configure()
	{
		$this->setName('taxonomy:remove')
			->setDescription('Remove an available taxonomy')
			->setHelp('This command allows you remove an available taxonomy out project')
			->addArgument(
				'name',
				InputArgument::REQUIRED,
				'Available Taxonomy Class Name'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$fileName = $input->getArgument('name');
		$path = '/App' . DIRECTORY_SEPARATOR . 'Taxonomies';
		$fileExtension = '.php';
		$filePath = $path . DIRECTORY_SEPARATOR . $fileName . $fileExtension;

        if (Storage::has($filePath)) {
        	Storage::delete($filePath);
        } else {
        	$output->write("<error>This taxonomy does not exists: {$filePath}</error>", true);
        	return false;
        }

        BindingGenerator::remove('/app/Providers/TaxonomyServiceProvider.php', '\App\Taxonomies', $fileName);
        $output->write("<info>{$fileName} is removed</info>", true);
	}
}