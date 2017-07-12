<?php

namespace App\Providers;

use App\CustomPosts\SampleType;
use Illuminate\Support\ServiceProvider;

class CustomPostServiceProvider extends ServiceProvider
{
    /**
     * [$listen description]
     * @var array
     */
    public $listen = [
        SampleType::class,
    ];

    /**
     * [register description]
     * @return [type] [description]
     */
    public function register()
    {
        foreach ($this->listen as $class) {
            $this->resolveCustomPost($class);
        }
    }

    /**
     * [resolveCustomPost description]
     * @param  [type] $postType [description]
     * @return [type]           [description]
     */
    public function resolveCustomPost($postType)
    {
        return new $postType();
    }
}
