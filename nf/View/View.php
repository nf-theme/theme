<?php

namespace NF\View;

use NF\Traits\View as ViewTrait;

class View
{
    use ViewTrait;
    public function __construct()
    {
        $this->setViewPath(__DIR__ . '/../../resources/views');
        $this->setCachePath(__DIR__ . '/../../storage/cache');
    }
}
