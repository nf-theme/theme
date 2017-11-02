<?php

namespace NF\Models\Traits;

trait HasAttachment
{
    public function thumbnail()
    {
        return $this->belongsToMany(self::class, NFWP_DB_TABLE_PREFIX . 'postmeta', 'post_id', 'meta_value')->wherePivot('meta_key', '_thumbnail_id');
    }
    public function getThumbnail()
    {
        return $this->thumbnail->first()->guid ? $this->thumbnail->first()->guid : $this->defaultThumbnail;
    }
    public function getThePostThumbnail($size = 'thumbnail', $attr = '')
    {
        if ($this->thumbnail->first()->ID) {
            return $this->renderThumbnail((object) $this->getAttributes(), $this->thumbnail->first()->ID, $size, $attr);
        } else {
            return $this->renderDefaultThumbnail($size, $attr);
        }
    }
    public function renderDefaultThumbnail($size, $attr)
    {
        if ($this->defaultThumbnail) {
            if (is_int($this->defaultThumbnail)) {
                return $this->renderThumbnail((object) $this->getAttributes(), $this->defaultThumbnail, $size, $attr);
            } else {
                return "<img src=\"{$this->defaultThumbnail}\" alt=\"\" class=\"nf-default-thumbnail\" />";
            }
        } else {
            return '';
        }
    }

    public function renderThumbnail($post, $post_thumbnail_id, $size, $attr)
    {
        $size = apply_filters('post_thumbnail_size', $size);

        if ($post_thumbnail_id) {

            /**
             * Fires before fetching the post thumbnail HTML.
             *
             * Provides "just in time" filtering of all filters in wp_get_attachment_image().
             *
             * @since 2.9.0
             *
             * @param int          $post_id           The post ID.
             * @param string       $post_thumbnail_id The post thumbnail ID.
             * @param string|array $size              The post thumbnail size. Image size or array of width
             *                                        and height values (in that order). Default 'post-thumbnail'.
             */
            do_action('begin_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size);
            if (in_the_loop()) {
                update_post_thumbnail_cache();
            }

            $html = wp_get_attachment_image($post_thumbnail_id, $size, false, $attr);

            /**
             * Fires after fetching the post thumbnail HTML.
             *
             * @since 2.9.0
             *
             * @param int          $post_id           The post ID.
             * @param string       $post_thumbnail_id The post thumbnail ID.
             * @param string|array $size              The post thumbnail size. Image size or array of width
             *                                        and height values (in that order). Default 'post-thumbnail'.
             */
            do_action('end_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size);

        } else {
            $html = '';
        }
        /**
         * Filters the post thumbnail HTML.
         *
         * @since 2.9.0
         *
         * @param string       $html              The post thumbnail HTML.
         * @param int          $post_id           The post ID.
         * @param string       $post_thumbnail_id The post thumbnail ID.
         * @param string|array $size              The post thumbnail size. Image size or array of width and height
         *                                        values (in that order). Default 'post-thumbnail'.
         * @param string       $attr              Query string of attributes.
         */
        return apply_filters('post_thumbnail_html', $html, $post->ID, $post_thumbnail_id, $size, $attr);
    }
}
