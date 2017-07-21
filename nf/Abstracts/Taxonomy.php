<?php
namespace NF\Abstracts;

use NF\Interfaces\TaxonomyInterface;

class Taxonomy implements TaxonomyInterface
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        add_action('init', [$this, 'createTaxonomy'], 10);
    }

    /**
     * [getArgs description]
     * @return [type] [description]
     */
    public function getArgs()
    {
        $hierarchical = true;

		if (isset($this->args['hierarchical'])) {
			$hierarchical = (bool) $this->args['hierarchical'];
		}

        $labels = [
            'name'                       => $this->plural,
            'single_name'                => $this->single,
            'search_items'               => sprintf(__('Search %s'), $this->plural),
            'all_items'                  => sprintf( __('All %s'), $this->plural),
            'edit_item'                  => sprintf( __('Edit %s'), $this->single),
            'view_item'                  => sprintf( __('View %s'), $this->single),
            'update_item'                => sprintf( __('Update %s'), $this->single),
            'add_new_item'               => sprintf( __('Add New %s'), $this->single),
            'new_item_name'              => sprintf( __('New %s Name'), $this->single),
            'not_found'                  => sprintf( __('No %s found.'), $this->plural),
            'no_terms'                   => sprintf( __('No %s'), $this->plural),
            // Hierarchical stuff
            'parent_item'       => $hierarchical ? sprintf( __( 'Parent %s'), $this->single ) : null,
            'parent_item_colon' => $hierarchical ? sprintf( __( 'Parent %s:'), $this->single ) : null,
            // Non-hierarchical stuff
            'popular_items'              => $hierarchical ? null : sprintf( __( 'Popular %s'), $this->plural ),
            'separate_items_with_commas' => $hierarchical ? null : sprintf( __( 'Separate %s with commas'), $this->plural ),
            'add_or_remove_items'        => $hierarchical ? null : sprintf( __( 'Add or remove %s'), $this->plural ),
            'choose_from_most_used'      => $hierarchical ? null : sprintf( __( 'Choose from the most used %s'), $this->plural ),
        ];

        $default = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['hierarchical' => $hierarchical, 'slug' => $this->slug],
        ];

    	$this->args = wp_parse_args($this->args, $default);
    	$this->args['labels'] = wp_parse_args($this->args['labels'], $labels);

    	return $this->args;
    }

    /**
     * [createTaxonomy description]
     * @return [type] [description]
     */
    public function createTaxonomy()
    {
        register_taxonomy($this->slug, $this->objectType, $this->getArgs());
    }
}
