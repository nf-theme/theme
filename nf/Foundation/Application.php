<?php

namespace NF\Foundation;

use App\Providers\AppServiceProvider;
use App\Providers\CustomPostServiceProvider;
use App\Providers\LogServiceProvider;
use App\Providers\ShortCodeServiceProvider;
use App\Providers\TaxonomyServiceProvider;
use App\Providers\WidgetServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Facade;

class Application extends Container
{
    public $app_path;
    public $app_config;

    /**
     * All of the registered service providers.
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    public function __construct($app_path)
    {
        $this->setAppPath($app_path);
        $this->app_config = require $this->configPath() . DIRECTORY_SEPARATOR . 'app.php';
        Facade::setFacadeApplication($this);
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new AppServiceProvider($this));
        $this->register(new LogServiceProvider($this));
        $this->register(new ShortCodeServiceProvider($this));
        $this->register(new CustomPostServiceProvider($this));
        $this->register(new TaxonomyServiceProvider($this));
        $this->register(new WidgetServiceProvider($this));
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  array  $options
     * @param  bool   $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false)
    {
        if (($registered = $this->getProvider($provider)) && !$force) {
            return $registered;
        }
        // If the given "provider" is a string, we will resolve it, passing in the
        // application instance automatically for the developer. This is simply
        // a more convenient way of specifying your service provider classes.
        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        if (method_exists($provider, 'register')) {
            $provider->register();
        }
        $this->markAsRegistered($provider);
        // If the application has already booted, we will call this boot method on
        // the provider class so it has an opportunity to do its boot logic and
        // will be ready for any usage by this developer's application logic.

        if ($this->booted) {
            $this->bootProvider($provider);
        }
        return $provider;
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param  string  $provider
     * @return \Illuminate\Support\ServiceProvider
     */
    public function resolveProvider($provider)
    {
        return new $provider($this);
    }

    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }
    /**
     * Boot the given service provider.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }
    }

    /**
     * Mark the given provider as registered.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[]                    = $provider;
        $this->loadedProviders[get_class($provider)] = true;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);
        return Arr::first($this->serviceProviders, function ($value) use ($name) {
            return $value instanceof $name;
        });
    }

    /**
     * Gets the value of app_path.
     *
     * @return mixed
     */
    public function appPath()
    {
        return $this->app_path;
    }

    /**
     * Gets the value of storage_path.
     *
     * @return mixed
     */
    public function storagePath()
    {
        return $this->appPath() . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * Get app config file path
     *
     * @return string
     */
    public function configPath()
    {
        return $this->appPath() . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * Get config value by key
     *
     * @param string
     * @return string
     */
    public function config($key)
    {
        return $this->app_config[$key];
    }

    /**
     * Sets the value of app_path.
     *
     * @param mixed $app_path the app path
     *
     * @return self
     */
    public function setAppPath($app_path)
    {
        $this->app_path = $app_path;

        return $this;
    }

    /**
     * Gets the The names of the loaded service providers.
     *
     * @return array
     */
    public function getLoadedProviders()
    {
        return $this->loadedProviders;
    }

    /**
     * Determine if we are running in the console.
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli' || php_sapi_name() == 'phpdbg';
    }
}
