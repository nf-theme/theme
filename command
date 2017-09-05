#!/usr/bin/env php
<?php

define('WP_USE_THEMES', false);

require dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-blog-header.php';

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use NF\Commands\GetWidgetCommand;
use NF\Commands\ListPostTypeCommand;
use NF\Commands\ListShortcodeCommand;
use NF\Commands\ListTaxonomyCommand;
use NF\Commands\ListWidgetCommand;
use NF\Commands\MakePostTypeCommand;
use NF\Commands\MakeShortCodeCommand;
use NF\Commands\MakeTaxonomyCommand;
use NF\Commands\MakeViewCommand;
use NF\Commands\MakeWidgetCommand;
use NF\Commands\RemovePostTypeCommand;
use NF\Commands\RemoveShortcodeCommand;
use NF\Commands\RemoveTaxonomyCommand;
use NF\Commands\RemoveWidgetCommand;
use NF\Facades\App;

$app = new \NF\Foundation\Application(__DIR__);

$application = new \Symfony\Component\Console\Application();

$application->add(new MakeViewCommand());
$application->add(new MakeShortCodeCommand());
$application->add(new MakePostTypeCommand());
$application->add(new MakeWidgetCommand());
$application->add(new MakeTaxonomyCommand());
$application->add(new GetWidgetCommand());
$application->add(new RemoveWidgetCommand());
$application->add(new ListWidgetCommand());
$application->add(new RemoveShortcodeCommand());
$application->add(new ListShortcodeCommand());
$application->add(new RemovePostTypeCommand());
$application->add(new ListPostTypeCommand());
$application->add(new RemoveTaxonomyCommand());
$application->add(new ListTaxonomyCommand());

$application->run();
