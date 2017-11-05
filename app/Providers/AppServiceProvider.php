<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use NF\Database\DBManager;
use NF\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public $listen = [

    ];

    public function register()
    {
        $adapter = new Local($this->app->appPath());
        $this->app->singleton('filesystem', function ($app) use ($adapter) {
            return new Filesystem($adapter);
        });

        $this->app->singleton('request', function ($app) {
            return \Illuminate\Http\Request::capture();
        });

        $this->app->singleton(DBManager::class, function ($app) {
            return new DBManager;
        });
    }
}
