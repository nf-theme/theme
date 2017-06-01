<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:test')
            ->setDescription('Test command.')
            ->setHelp('This command for testing only...')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            ->addOption('option', 'o', InputOption::VALUE_REQUIRED, 'Your option here', 'default option');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("Hello {$input->getArgument('username')}! Test command is works :)", true);
        $output->write("<info>Options passed: {$input->getOption('option')}</info>", true);
    }
}
