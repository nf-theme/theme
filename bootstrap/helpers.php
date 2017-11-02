<?php
global $wpdb;

define('NFWP_DB_TABLE_PREFIX', $wpdb->prefix);

if (!function_exists('view')) {
    /**
     * Use template engine quicker
     * @param string $path
     * @param array $data
     * @param  boolean $echo
     * @return mixed
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
     * Get resource uri
     * @param string
     */
    function asset($assets)
    {
        return wp_slash(get_stylesheet_directory_uri() . '/dist/' . $assets);
    }
}

if (!function_exists('title')) {
    /**
     * Generate page title
     *
     * @return string
     */
    function title()
    {
        if (is_home() || is_front_page()) {
            return get_bloginfo('name');
        }

        if (is_archive()) {
            $obj = get_queried_object();
            return $obj->name . ' - ' . get_bloginfo('name');
        }

        if (is_404()) {
            return '404 page not found - ' . get_bloginfo('name');
        }

        return get_the_title() . ' - ' . get_bloginfo('name');
    }
}

if (!function_exists('createExcerptFromContent')) {
    /**
     * this function will create an excerpt from post content
     *
     * @param  string $content
     * @param  int    $limit
     * @param  string $readmore
     * @since  1.0.0
     * @return string $excerpt
     */
    function createExcerptFromContent($content, $limit = 50, $readmore = '...')
    {
        if (!is_string($content)) {
            throw new Exception("first parameter must be a string.");
        }

        if ($content == '') {
            throw new Exception("first parameter is not empty.");
        }

        if (!is_int($limit)) {
            throw new Exception("second parameter must be the number.");
        }

        if ($limit <= 0) {
            throw new Exception("second parameter must greater than 0.");
        }

        $words = explode(' ', $content);

        if (count($words) <= $limit) {
            $excerpt = $words;
        } else {
            $excerpt = array_chunk($words, $limit)[0];
        }

        return strip_tags(implode(' ', $excerpt)) . $readmore;
    }
}

if (!function_exists('getPostImage')) {
    /**
     * [getPostImage description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function getPostImage($id, $imageSize = '')
    {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($id), $imageSize);
        return (!$img) ? '' : $img[0];
    }
}
