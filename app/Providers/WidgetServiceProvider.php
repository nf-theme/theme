<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    public $listen = [
    ];

    public function register()
    {
        foreach ($this->listen as $class) {
            $this->resolveWidget($class);
        }
    }

    /**
     * Resolve a widget instance from the class name.
     *
     * @param  string  $widget
     * @return widget instance
     */
    public function resolveWidget($widget)
    {
        return new $widget();
    }
}
