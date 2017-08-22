<?php

namespace NF\View;

use NF\Traits\View as ViewTrait;

class View
{
    use ViewTrait;
    
    public function __construct()
    {
        $this->setRootPath();
        $this->setViewDir(DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views');
        $this->setCacheDir(DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache');
        $this->setViewPath($this->rootPath . $this->viewDir);
        $this->setCachePath($this->rootPath . $this->cacheDir);
        $this->createCacheDir();
    }
}
