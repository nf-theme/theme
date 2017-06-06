<?php

namespace NF\Abstracts;

use NF\Interfaces\CustomPostInterface;

class CustomPost implements CustomPostInterface
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        add_action('init', [$this, 'registerPostType']);
    }

    /**
     * [registerPostType description]
     * @return [type] [description]
     */
    public function registerPostType()
    {
        $result = register_post_type($this->type, $this->getArgs());

        if (is_wp_error($result)) {
            wp_die($result->get_error_message());
        };
    }

    /**
     * [getArgs description]
     * @return [type] [description]
     */
    public function getArgs()
    {
        $labels = array(
            'name'               => $this->plural,
            'singular_name'      => $this->single,
            'add_new'            => sprintf(__('Add New %s', ''), $this->single),
            'add_new_item'       => sprintf(__('Add New %s', ''), $this->single),
            'edit_item'          => sprintf(__('Edit %s', ''), $this->single),
            'new_item'           => sprintf(__('New %s', ''), $this->single),
            'all_items'          => sprintf(__('All %s', ''), $this->plural),
            'view_item'          => sprintf(__('View %s', ''), $this->single),
            'search_items'       => sprintf(__('Search %s', ''), $this->plural),
            'not_found'          => sprintf(__('No %s', ''), $this->plural),
            'not_found_in_trash' => sprintf(__('No %s found in Trash', ''), $this->plural),
            'parent_item_colon'  => isset($this->args['hierarchical']) && $this->args['hierarchical'] ? sprintf(__('Parent %s:', ''), $this->single ) : null,
            'menu_name'          => $this->plural,
            'insert_into_item'      => sprintf(__('Insert into %s', ''), strtolower($this->single)),
            'uploaded_to_this_item' => sprintf(__( 'Uploaded to this %s', ''), strtolower($this->single)),
            'items_list'            => sprintf(__('%s list', ''), $this->plural),
            'items_list_navigation' => sprintf(__('%s list navigation', ''), $this->plural),
            'filter_items_list'     => sprintf(__('Filter %s list', ''), strtolower($this->plural))
        );

        $defaults = array(
            'labels'             => [],
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'has_archive'        => true,
            'supports'           => ['title', 'editor', 'excerpt', 'thumbnail'],
        );

        $this->args = wp_parse_args($this->args, $defaults);

        $this->args['labels'] = wp_parse_args($this->args['labels'], $labels);

        return $this->args;
    }
}
