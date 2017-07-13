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
        'template-style',
        asset('styles/main.css'),
        false
    );
}

function theme_enqueue_scripts()
{
    wp_enqueue_script(
        'template-scripts',
        asset('scripts/main.js'),
        'jquery',
        '1.0',
        true
    );

    $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

    $params = array(
       'ajax_url' => admin_url('admin-ajax.php', $protocol)
    );

    wp_localize_script('template-scripts', 'ajax_obj', $params);
}
