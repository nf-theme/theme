<?php

namespace NF\Traits;

use Philo\Blade\Blade;
trait View
{
    public $viewPath;
    public $cachePath;
    public function render($view, $data = [])
    {
        $blade = new Blade($this->getViewPath(), $this->getCachePath());
        if (is_array($data)) {
            return $blade->view()->make($view, $data)->render();
        } else {
            throw new Exception("data pass into view must be an array", 1);
        }
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
