<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 *
 * @author Duy Nguyen
 * @since  1.1.0
 */

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';

add_filter('index_template_hierarchy', function (){
    return ['index.blade.php'];
});


/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {

    var_dump($template);

    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    view($template, $data);
    // Return a blank file to make WordPress happy
    return get_theme_file_path('index.php');
}, PHP_INT_MAX);
