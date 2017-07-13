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
            echo NF\View\Facades\View::render($path, $data);
        } else {
            return NF\View\Facades\View::render($path, $data);
        }
    }
}

if (!function_exists('asset')) {
    /**
     * [asset description]
     * @param [type] $assets [description]
     */
    function asset($assets)
    {
        return wp_slash(dirname(get_stylesheet_directory_uri()) . '/dist/' . $assets);
    }
}
