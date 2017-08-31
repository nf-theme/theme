<?php

namespace NF\Generator;

use App\Providers\ShortCodeServiceProvider;
use ReflectionObject;

abstract class Generator
{
    public function getProviderNameSpace($provider = null)
    {
        if (!isset($provider)) {
            $provider = new ShortCodeServiceProvider(null);
        }
        return (new ReflectionObject($provider))->getNamespaceName();
    }

}
