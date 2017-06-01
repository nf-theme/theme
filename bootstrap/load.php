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
    wp_enqueue_style('template-style', get_stylesheet_directory_uri() . '/dist/css/app.all.css', false);
}

function theme_enqueue_scripts()
{
    wp_enqueue_script('template-scripts', get_stylesheet_directory_uri() . '/dist/js/scripts.js', 'jquery');
    wp_enqueue_script('template-browserify', get_stylesheet_directory_uri() . '/dist/js/app.js', 'template-scripts');
}
