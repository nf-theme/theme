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

class GetWidgetCommand extends Command
{
	protected function configure()
	{
		$this->setName('widget:get')
			->setDescription('Get an available widget')
			->setHelp('This command allows you get an available widget to project')
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

		switch ($name) {
			case 'recent-posts':
				
				$fileName = 'RecentPostTypeWidget';
				$filePath = $path . DIRECTORY_SEPARATOR . $fileName . $fileExtension;
				$contents = Storage::read('/patterns/' . $fileName . $fileExtension);

				$compiled = BladeCompiler::compileString(
		            $contents
		        );

		        $compiled = "<?php \n{$compiled}";
				//$output->write($compiled);
				break;
			
			default:
				# code...
				break;
		}

        if (Storage::has($filePath)) {
            if ($input->getOption('override') !== false) {
                Storage::delete($filePath);
            } else {
                $output->write("<error>File exists: {$filePath}</error>", true);
                return false;
            }
        }

        Storage::write($filePath, $compiled);
        $output->write("<info>{$filePath}</info>", true);

        BindingGenerator::run('/app/Providers/WidgetServiceProvider.php', '\App\Widgets', $fileName);
	}
}