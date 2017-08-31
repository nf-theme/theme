<?php

namespace NF\Facades;

use Illuminate\Support\Facades\Facade;

class BindingGenerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return new \NF\Generator\BindingGenerator;
    }
}