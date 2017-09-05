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

class RemovePostTypeCommand extends Command
{
	protected function configure()
	{
		$this->setName('posttype:remove')
			->setDescription('Remove an available post type')
			->setHelp('This command allows you remove an available post type out project')
			->addArgument(
				'name',
				InputArgument::REQUIRED,
				'Available Post Type Name'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$fileName = $input->getArgument('name');
		$path = '/App' . DIRECTORY_SEPARATOR . 'CustomPosts';
		$fileExtension = '.php';
		$filePath = $path . DIRECTORY_SEPARATOR . $fileName . $fileExtension;

        if (Storage::has($filePath)) {
        	Storage::delete($filePath);
        } else {
        	$output->write("<error>This post type does not exists: {$filePath}</error>", true);
        	return false;
        }

        BindingGenerator::remove('/app/Providers/CustomPostServiceProvider.php', '\App\CustomPosts', $fileName);
        $output->write("<info>{$fileName} is removed</info>", true);
	}
}