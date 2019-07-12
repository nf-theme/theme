<?php
/**
 * create a shortcode that uses WP_Query to get data
 *
 * @since      1.0.0
 * @package    MSC
 * @subpackage listing
 * @author     Monkeyscode <monkeyscodestudio@gmail.com>
 */

namespace App\Shortcodes;

use NF\View\Facades\View;

class MscListing
{
    /**
     * name of shortcode
     *
     * @var string
     */
    protected $shortcodename = 'listing';

    /**
     * default attributes
     *
     * @var array
     */
    protected $defaultAtts = [
        'post_type'  => 'post',
        'per_page'   => 6,
        'user'       => null,
        'cat'        => null,
        'categories' => '',
        'tag'        => null,
        'taxonomy'   => null,
        'status'     => 'publish',
        'excludes'   => '',
        'orderby'    => '',
        'paged'      => 'no',
        'filter'     => 'no',
        'layout'     => '',
    ];

    /**
     * attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * constructor
     *
     * @since 1.0.0
     *
     * @param string $template
     */
    public function __construct()
    {
        add_shortcode($this->shortcodename, [$this, 'callback']);
    }

    /**
     * set custom attributes
     *
     * @param  array $atts
     * @return void
     */
    public function setCustomAttrs($atts)
    {
        if (!is_array($atts)) {
            wp_die(__('setCustomAttrs(): the first paramter must be an array.', 'msc'));
        }

        $this->attributes = wp_parse_args($atts, $this->defaultAtts);
    }

    /**
     * get attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return (!empty($this->attributes)) ? $this->attributes : $this->defaultAtts;
    }

    /**
     * excute and render template
     *
     * @since 1.0.0
     *
     * @param array  $atts
     * @param string $cont
     */
    public function callback($atts, $cont)
    {
        global $wp_query;

        $opts = shortcode_atts($this->getAttributes(), $atts);

        $paged = @$wp_query->query['paged'];

        if ($paged === null || $opts['paged'] == 'no') {
            $paged = 1;
        }

        $args = [
            'post_type'        => $opts['post_type'],
            'posts_per_page'   => $opts['per_page'],
            'paged'            => $paged,
            'post_status'      => explode(',', $opts['status']),
            'suppress_filters' => 0,
        ];

        // category id
        if ($opts['cat'] != null) {
            $args['cat'] = $opts['cat'];
        }

        // category name
        if ($opts['categories'] != '') {
            $args['category_name'] = $opts['categories'];
        }

        // tags
        if ($opts['tag'] != null) {
            $args['tag'] = $opts['tag'];
        }

        if ($opts['excludes'] != '') {
            $exccludes            = explode(',', $opts['excludes']);
            $args['post__not_in'] = $exccludes;
        }

        if ($opts['user'] != null) {
            $args['author__in'] = explode(',', $opts['user']);
        }

        if ($opts['taxonomy'] != null && $opts['taxonomy'] != '') {

            $taxonomies = explode(',', $opts['taxonomy']);

            if (is_array($taxonomies)) {

                foreach ($taxonomies as $taxonomy) {
                    if ($taxonomy != '') {
                        preg_match_all("/(?P<tax>.*)\((?P<termstr>.*)\)/", $taxonomy, $matches);
                        $terms      = explode(':', $matches['termstr'][0]);
                        $taxQuery[] = array(
                            'taxonomy' => $matches['tax'][0],
                            'field'    => 'term_id',
                            'terms'    => $terms,
                            'operator' => 'IN',
                        );
                    }
                }

                $taxQuery['relation'] = 'OR';
            }
        }

        if ($opts['filter'] == 'yes') {
            foreach ($_GET as $key => $val) {
                if ($val == '') {
                    continue;
                }

                if (strpos($key, 'tax_') !== false) {
                    $tax        = explode('tax_', $key);
                    $taxonomy   = @$tax[1];
                    $taxQuery[] = array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'term_id',
                        'terms'    => [$val],
                        'operator' => 'IN',
                    );
                }

                if (isset($_GET['date_range']) && $_GET['date_range'] != '') {
                    $range = $_GET['date_range'];

                    $dateQuery[] = [
                        [
                            'after' => $range . ' days ago',
                        ],
                    ];
                }
            }

            $taxQuery['relation'] = 'AND';

        }

        $pages = $opts['per_page'];
        add_action('pre_get_posts', function ($query) use ($pages) {
            $query->set('posts_per_page', $pages);
        });

        if (isset($taxQuery)) {
            $args['tax_query'] = $taxQuery;
        }

        if (isset($dateQuery)) {
            $args['date_query'] = $dateQuery;
        }

        ob_start();

        $this->beforeWrap('div', ['class' => 'msc-listing', 'id' => 'msc_listing']);

        $query = new \WP_Query($args);

        $this->handle($query, $opts);

        wp_reset_postdata();

        $this->afterWrap('div');

        if ($opts['paged'] == 'yes') {
            $this->createPagination($query, $paged);
        }

        return ob_get_clean();
    }

    /**
     * handle data
     *
     * @since  1.0.0
     * @param  object $query
     * @param  array  $opts
     * @return mix
     */
    public function handle($query, $opts)
    {
        if ($query->have_posts()) {

            while ($query->have_posts()) {

                $query->the_post();

                $data = [
                    'title' => get_the_title(),
                ];

                if ($opts['layout'] != '') {
                    echo View::render($opts['layout'], $data);
                } else {
                    echo 'Pass a view to display your data.';
                }
            }
        }
    }

    /**
     * Open tag wrap
     *
     * @param  string $tag  open html tag
     * @param  array  $atts attributes of tag
     * @since  1.0.0
     * @return void
     */
    public function beforeWrap($tag = 'div', $atts = [])
    {
        $tag  = apply_filters('wrap_tag', $tag);
        $atts = apply_filters('wrap_atts', $atts);
        if (!empty($atts)) {
            foreach ($atts as $att => $value) {
                $attributes[] = "$att='$value'";
            }
            printf('<%s %s>', $tag, implode(' ', $attributes));
        } else {
            printf('<%s>', $tag);
        }
    }

    /**
     * Close tag wrap. It must be corresponding tag which pass in beforeWrap method
     *
     * @param  string $tag close html tag
     * @since  1.0.0
     * @return void
     */
    public function afterWrap($tag = 'div')
    {
        $tag = apply_filters('wrap_tag', $tag);
        printf('</%s>', $tag);
    }

    /**
     * create pagination
     *
     * @param  object $loop
     * @param  int    $paged
     * @since  1.0.0
     * @return void
     */
    private function createPagination($query, $paged)
    {
        $big = 999999999;

        $paged_wrap = apply_filters(
            'paged_wrap',
            array(
                'tag'       => 'div',
                'class'     => 'pagination',
                'id'        => '',
                'prev_text' => '« Previous',
                'next_text' => 'Next »',
            )
        );

        $paged_args = array(
            'base'               => str_replace(
                $big,
                '%#%',
                esc_url(get_pagenum_link($big))
            ),
            'format'             => '?paged=%#%',
            'total'              => $query->max_num_pages,
            'current'            => max(1, $paged),
            'show_all'           => false,
            'end_size'           => 1,
            'mid_size'           => 2,
            'prev_next'          => true,
            'prev_text'          => $paged_wrap['prev_text'],
            'next_text'          => $paged_wrap['next_text'],
            'type'               => 'list',
            'add_args'           => false,
            'add_fragment'       => '',
            'before_page_number' => '',
            'after_page_number'  => '',
        );

        printf(
            '<%s class="%s" id="%s">',
            $paged_wrap['tag'],
            $paged_wrap['class'],
            $paged_wrap['id']
        );

        echo paginate_links($paged_args);

        printf(
            '</%s>',
            $paged_wrap['tag']
        );
    }
}
