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

class RemoveWidgetCommand extends Command
{
	protected function configure()
	{
		$this->setName('widget:remove')
			->setDescription('Remove an available widget')
			->setHelp('This command allows you remove an available widget to project')
			->addArgument(
				'name',
				InputArgument::REQUIRED,
				'Available Widget Name'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = $input->getArgument('name');
		$path = '/App' . DIRECTORY_SEPARATOR . 'Widgets';
		$fileExtension = '.php';
		$filePath = $path . DIRECTORY_SEPARATOR . $name . $fileExtension;

        if (Storage::has($filePath)) {
        	Storage::delete($filePath);
        } else {
        	$output->write("<error>This widget file does not exists: {$filePath}</error>");
        	return false;
        }

        BindingGenerator::remove('/app/Providers/WidgetServiceProvider.php', '\App\Widgets', $name);
	}
}