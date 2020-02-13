<?php

namespace App\Providers;

use App\Blocks\SliderBlock\SliderBlock;
use App\Blocks\FancyBoxBlock;
use App\Blocks\PostBlock;
use App\Blocks\PostSliderBlock;
use Illuminate\Support\ServiceProvider;

class BlockServiceProvider extends ServiceProvider
{
    public $listen = [
        SliderBlock::class,
        FancyBoxBlock::class,
        PostBlock::class,
        PostSliderBlock::class,
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
