<?php

namespace NF\CompileBladeString\Facade;

use Illuminate\Support\Facades\Facade;

class BladeCompiler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new \NF\CompileBladeString\BladeCompiler;
    }
}
