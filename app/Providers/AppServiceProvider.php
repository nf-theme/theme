<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use NF\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public $listen = [

    ];

    public function register()
    {
    	//var_dump($this->app->appPath()); DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views'
        $adapter = new Local($this->app->appPath());
        $this->app->singleton('filesystem', function ($app) use ($adapter) {
            return new Filesystem($adapter);
        });
    }
}
