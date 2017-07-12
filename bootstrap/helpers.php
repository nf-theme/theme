<?php

if (!function_exists('view')) {
    /**
     * [view description]
     * @param  [type]  $path [description]
     * @param  array   $data
     * @param  boolean $echo [description]
     * @return [type]        [description]
     */
    function view($path, $data = [], $echo = true)
    {
        if ($echo) {
            echo View::render($path, $data);
        } else {
            return View::render($path, $data);
        }
    }
}
