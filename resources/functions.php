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
 * @var [type]
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type){
    add_filter($type . '_template_hierarchy', function ($templates){

        return collect($templates)->flatMap(function ($template){
            $baseTemplate = basename($template, '.php');
            return ['views/' . $baseTemplate . '.blade.php'];
        })->toArray();
    });
});

/**
 * [add_filter description]
 * @var [type]
 */
add_filter('template_include', function ($template){

    /**
     * @todo how to pass data for coresponding template
     */

    view(basename($template, '.blade.php'));

    // return an empty template to disable double return content
    return get_theme_file_path('index.php');
});
