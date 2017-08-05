<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 *
 * @author Duy Nguyen
 * @since  1.1.0
 */

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';

/**
 * Template Hierarchy
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type){
    add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filterTemplates');
});

/**
 * [add_filter description]
 *
 */
add_filter('template_include', function ($template){

    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template){
        return apply_filters("templates/{$class}/data", $data);
    }, []);

    /**
     * @todo find the good way to pass data for coresponding template
     */
    view(basename($template, '.blade.php'), $data);

    // return an empty template to disable double return content
    return get_theme_file_path('index.php');
});
