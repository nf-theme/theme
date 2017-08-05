<?php
//namespace App;

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

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filterTemplates($templates)
{
    return collect($templates)
        ->map(function ($template) {
            return preg_replace('#\.(blade\.)?php$#', '', ltrim($template));
        })
        ->flatMap(function ($template) {
            $paths = apply_filters('templates/filterTemplates/paths', ['views', 'resources/views']);
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                        "{$template}.blade.php",
                        "{$template}.php",
                    ];
                });
        })
        ->filter()
        ->unique()
        ->all();
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
            wp_die(__('first parameter must be a string.', ''));
        }

        if ($content == '') {
            wp_die(__('first parameter is not empty.', ''));
        }

        if (!is_int($limit)) {
            wp_die(__('second parameter must be the number.', ''));
        }

        if ($limit <= 0) {
            wp_die(__('second parameter must greater than 0.'));
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
