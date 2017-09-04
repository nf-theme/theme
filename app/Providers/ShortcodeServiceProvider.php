<?php

namespace App\Providers;

use App\Shortcodes\ListingShortcode;
use App\Shortcodes\SampleShortCode;
use Illuminate\Support\ServiceProvider;

class ShortCodeServiceProvider extends ServiceProvider
{
    public $listen = [
        SampleShortCode::class,
        ListingShortcode::class,
    ];

    public function register()
    {
        foreach ($this->listen as $class) {
            $this->resolveShortCode($class);
        }
    }

    /**
     * Resolve a short_code instance from the class name.
     *
     * @param  string  $short_code
     * @return short_code instance
     */
    public function resolveShortCode($short_code)
    {
        return new $short_code();
    }
}
