<?php

namespace App\Providers;

use App\Widgets\RecentPostTypeWidget;
use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    public $listen = [
        RecentPostTypeWidget::class,
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
