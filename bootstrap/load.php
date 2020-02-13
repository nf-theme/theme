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
        asset('app.css'),
        false
    );
     // wp_enqueue_style( 'slick', 'http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1' );
    // wp_enqueue_style( 'slick-theme', 'http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), '1.8.1' );
}

function theme_enqueue_scripts()
{
    // wp_enqueue_script( 'slick', 'http://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', false );
    wp_enqueue_script(
        'template-scripts',
        asset('app.js'),
        'slick',
        '1.0',
        true
    );

    // wp_enqueue_script(
    //     'slick-scripts', get_template_directory_uri().'/block/custom-slick.js', 'jquery', '1.0', true );

    $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

    $params = array(
        'ajax_url' => admin_url('admin-ajax.php', $protocol),
    );
    wp_localize_script('template-scripts', 'ajax_obj', $params);
}

if (!function_exists('themeSetup')) {
    /**
     * setup support for theme
     *
     * @return void
     */
    function themeSetup()
    {
        // Register menus
        register_nav_menus(array(
            'main-menu' => __('Main Menu', 'vicoders'),
        ));

    }

    add_action('after_setup_theme', 'themeSetup');
}

if (!function_exists('themeSidebars')) {
    /**
     * register sidebar for theme
     *
     * @return void
     */
    function themeSidebars()
    {
        $sidebars = [
            [
                'name'          => __('Sidebar', 'vicoders'),
                'id'            => 'main-sidebar',
                'description'   => __('Main Sidebar', 'vicoders'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
        ];

        foreach ($sidebars as $sidebar) {
            register_sidebar($sidebar);
        }
    }

    add_action('widgets_init', 'themeSidebars');
}
