<?php

namespace NF\Commands;

use NF\Facades\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeViewCommand extends Command
{
    protected function configure()
    {
        $this->setName('make:view')
            ->setDescription('Create new view file')
            ->setHelp('php command make:view {view}')
            ->addArgument('view', InputArgument::REQUIRED, 'View name');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $output->write("<info>Options passed: {$input->getOption('option')}</info>", true);
        $path = 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $input->getArgument('view') . '.blade.php';
        if (Storage::has($path)) {
            $output->write("<error>File exists: {$path}</error>", true);
        } else {
            Storage::put($path, '');
            $output->write("<info>{$path}</info>", true);
        }

    }

    public function resolveViewPath($raw)
    {

    }
}
