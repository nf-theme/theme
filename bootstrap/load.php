<?php
/**
 * Enqueue scripts and stylesheet
 */
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');
add_action('wp_enqueue_scripts', 'theme_enqueue_style');
/**
 * Theme support
 */
add_theme_support('post-thumbnails');

function theme_enqueue_style()
{
    wp_enqueue_style(
        'template-vendor',
        get_stylesheet_directory_uri() . '/dist/styles/vendor.css',
        false
    );
    wp_enqueue_style(
        'template-style',
        get_stylesheet_directory_uri() . '/dist/styles/main.css',
        false
    );
}

function theme_enqueue_scripts()
{
    wp_enqueue_script(
        'template-packages',
        get_stylesheet_directory_uri() . '/dist/scripts/vendor.js',
        'jquery',
        '1.0',
        true
    );
    wp_enqueue_script(
        'template-scripts',
        get_stylesheet_directory_uri() . '/dist/scripts/main.js',
        'jquery',
        '1.0',
        true
    );
}
