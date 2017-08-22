<?php

namespace NF\Traits;

use NF\Facades\Storage;
use Philo\Blade\Blade;

trait View
{
    public $viewPath;

    public $cachePath;

    public $rootPath;

    public $viewDir;

    public $cacheDir;

    public function render($view, $data = [])
    {
        $this->writeFile($view);

        $blade = new Blade($this->getViewPath(), $this->getCachePath());

        if (is_array($data)) {
            return $blade->view()->make($view, $data)->render();
        } else {
            throw new Exception("data pass into view must be an array", 1);
        }
    }

    public function writeFile($path)
    {
        $fullPath = $this->viewDir . DIRECTORY_SEPARATOR . $this->transformDotToSlash($path) . '.blade.php';

        if (!Storage::has($fullPath)) {
            Storage::createDir(dirname($fullPath));
            return Storage::write($fullPath, '');
        }
        return false;
    }

    public function transformDotToSlash($path)
    {
        return str_replace('.', '/', $path);
    }

    public function createCacheDir()
    {
        if (!Storage::has($this->cacheDir)) {
            return Storage::createDir($this->cacheDir);
        }
        return false;
    }

    /**
     * Gets the value of viewPath.
     *
     * @return mixed
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Sets the value of viewPath.
     *
     * @param mixed $viewPath the view path
     *
     * @return self
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;

        return $this;
    }

    public function setRootPath()
    {
        $this->rootPath = dirname(dirname(__DIR__));
    }

    public function setViewDir($viewDir)
    {
        $this->viewDir = $viewDir;
    }

    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * Gets the value of cachePath.
     *
     * @return mixed
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * Sets the value of cachePath.
     *
     * @param mixed $cachePath the cache path
     *
     * @return self
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;

        return $this;
    }
}
