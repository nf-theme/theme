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
			)
			->addOption(
				'layout',
				null,
				InputOption::VALUE_REQUIRED,
				'Determine which layout of this widget that you want to render',
				1
			)
			->addOption(
				'override',
				null,
				InputOption::VALUE_NONE,
				false
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = $input->getArgument('name');
		$layoutNumber = $input->getOption('layout');

		$path = '/App' . DIRECTORY_SEPARATOR . 'Widgets';
		$fileExtension = '.php';

		switch ($name) {
			case 'recent-posts':
				$fileName = 'RecentPostTypeWidget';
				break;
			case 'search-form':
				$fileName = 'SearchWidget';
				break;
			
			default:
				# code...
				break;
		}

		$filePath = $path . DIRECTORY_SEPARATOR . $fileName . $fileExtension;
		$patternPath = '/patterns' . DIRECTORY_SEPARATOR . $fileName . $fileExtension;

		if (Storage::has($patternPath)) {
			$contents = Storage::read($patternPath);
			$compiled = BladeCompiler::compileString(
	            $contents,
	            [
	            	'layout' => $layoutNumber
	            ]
	        );
	        $compiled = "<?php \n{$compiled}";
		} else {
			$output->write("<error>Pattern file does not exists: {$patternPath}</error>", true);
			return false;
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