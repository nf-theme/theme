<?php
/**
 * register a taxonomy for custom post type
 *
 * @since      1.0.0
 * @package    MSC
 * @subpackage Cpt
 * @author     MonkeyscodeStudio <monkeyscodestudio@gmail.com>s
 */

namespace App\Taxonomies;

class BaseTaxonomy {
    /**
     * @var string
     */
    protected $objectType;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $single;

    /**
     * @var string
     */
    protected $plural;

    /**
     * @var array
     */
    protected $args;

    /**
     * constructor
     *
     * @param array  $taxonomy entry input config array for taxonomy
     *               [
     *                  'slug' => 'taxonomy_slug',
     *                  'single' => 'Single Label Taxonomy',
     *                  'plural' => 'Plural Label Taxonomy'
     *               ]
     *
     * @param string $type     post type name/slug
     * @param array  $args
     */
    public function __construct($taxonomy, $type, $args = []) {
        if (!is_array($taxonomy) || !is_array($args)) {
            wp_die(__('It must be an array.'));
        }

        if (empty($taxonomy)) {
            wp_die(__('It is not empty.'));
        }

        if (!is_string($type)) {
            wp_die(__('It must be string.'));
        }

        if ($type == '') {
            wp_die(__('It is not empty.'));
        }

        $this->slug = $taxonomy['slug'];
        $this->single = @$taxonomy['single'];
        $this->plural = @$taxonomy['plural'];
        $this->objectType = $type;
        $this->args = $args;

        add_action('init', [$this, 'createTaxonomy'], 10);
    }

    /**
     * merge arguments with default arguments and return it
     *
     * @return array
     */
    public function getArgs() {
        $hierarchical = true;

		if (isset($this->args['hierarchical'])) {
			$hierarchical = (bool) $this->args['hierarchical'];
		}

        $labels = [
            'name'                       => $this->plural,
            'single_name'                => $this->single,
            'search_items'               => sprintf(__('Search %s', 'monkeyscode'), $this->plural),
            'all_items'                  => sprintf(__('All %s', 'monkeyscode'), $this->plural),
            'edit_item'                  => sprintf(__('Edit %s', 'monkeyscode'), $this->single),
            'view_item'                  => sprintf(__('View %s', 'monkeyscode'), $this->single),
            'update_item'                => sprintf(__('Update %s', 'monkeyscode'), $this->single),
            'add_new_item'               => sprintf(__('Add New %s', 'monkeyscode'), $this->single),
            'new_item_name'              => sprintf(__('New %s Name', 'monkeyscode'), $this->single),
            'not_found'                  => sprintf(__('No %s found.', 'monkeyscode'), $this->plural),
            'no_terms'                   => sprintf(__('No %s', 'monkeyscode'), $this->plural),
            // Hierarchical stuff
            'parent_item'       => $hierarchical ? sprintf(__('Parent %s', 'monkeyscode'), $this->single) : null,
            'parent_item_colon' => $hierarchical ? sprintf(__('Parent %s:', 'monkeyscode'), $this->single) : null,
            // Non-hierarchical stuff
            'popular_items'              => $hierarchical ? null : sprintf(__('Popular %s', 'monkeyscode'), $this->plural),
            'separate_items_with_commas' => $hierarchical ? null : sprintf(__('Separate %s with commas', 'monkeyscode'), $this->plural),
            'add_or_remove_items'        => $hierarchical ? null : sprintf(__('Add or remove %s', 'monkeyscode'), $this->plural),
            'choose_from_most_used'      => $hierarchical ? null : sprintf(__('Choose from the most used %s', 'monkeyscode'), $this->plural),
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
     * register taxonomy
     *
     * @return void
     */
    public function createTaxonomy() {
        register_taxonomy($this->slug, $this->objectType, $this->getArgs());
    }

}
