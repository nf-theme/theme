#!/usr/bin/env php
<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$app = new \NF\Foundation\Application(__DIR__);

use NF\Commands\MakeViewCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new MakeViewCommand());

$application->run();