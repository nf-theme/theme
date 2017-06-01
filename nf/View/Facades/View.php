<?php

namespace NF\View\Facades;

use Illuminate\Support\Facades\Facade;

class View extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new \NF\View\View;
    }
}
