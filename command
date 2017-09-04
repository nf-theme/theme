#!/usr/bin/env php
<?php

define('WP_USE_THEMES', false);

require dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-blog-header.php';

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use NF\Facades\App;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use NF\Commands\GetWidgetCommand;
use NF\Commands\MakeCustomTypeCommand;
use NF\Commands\MakeShortCodeCommand;
use NF\Commands\MakeTaxonomyCommand;
use NF\Commands\MakeViewCommand;
use NF\Commands\MakeWidgetCommand;
use NF\Commands\RemoveWidgetCommand;

$app = new \NF\Foundation\Application(__DIR__);

$application = new \Symfony\Component\Console\Application();

$application->add(new MakeViewCommand());
$application->add(new MakeShortCodeCommand());
$application->add(new MakeCustomTypeCommand());
$application->add(new MakeWidgetCommand());
$application->add(new MakeTaxonomyCommand());
$application->add(new GetWidgetCommand());
$application->add(new RemoveWidgetCommand());

$application->run();
