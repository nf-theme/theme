<?php

namespace App\Blocks;

use App\Blocks\GutenburgBlock;

class PostSliderBlock extends GutenburgBlock
{
    public $name = 'vc_post_slider';
    public $title = 'VC Post Slider';
    public $description = 'VC Post Slider Gutenburg Block';
    public $category = 'formatting';
    public $icon = 'images-alt2';
    public $align = 'full';

    public function __construct()
    {
        add_action('acf/init', [$this, 'register_block']);
    }

    public function register_block()
    {
        if (function_exists('acf_register_block_type')) {
            acf_register_block_type([
                'name' => $this->name,
                'title' => $this->title,
                'description' => $this->description,
                'render_callback' => [$this,'render'],
                'category' => $this->category,
                'icon' => $this->icon,
                'align' => $this->align,
            ]);
        }
    }

    public function render($block)
    { 
        $id = 'postslider-' . $block['id'];
        if( !empty($block['anchor']) ) {
            $id = $block['anchor'];
        }

        // Create class attribute allowing for custom "className" and "align" values.
        $className = 'postslider';
        if( !empty($block['className']) ) {
            $className .= ' ' . $block['className'];
        }
        if( !empty($block['align']) ) {
            $className .= ' align' . $block['align'];
        }
        if( $is_preview ) {
            $className .= ' is-admin';
        }

        $vc_column = get_field('vc_column');
        $choose_term = get_field('choose_term');
        $term = get_field('term');
        $post_count = get_field('post_count');
        $order = get_field('order');
        $order_by = get_field('order_by');

        if ($choose_term == 'all') {
            $arg = [
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'orderby'             => 'post-date',
                'order '              => $order,
                'ignore_sticky_posts' => true,
                'posts_per_page'      => $post_count,
                'paged'               => false,
            ];
        }
        else {
           $arg = [
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'orderby'             => $order_by,
                'order '              => $order,
                'ignore_sticky_posts' => true,
                'posts_per_page'      => $post_count,
                'paged'               => false,
                'tax_query'           => [
                    [
                        'taxonomy'  => 'category',
                        'field'     => 'term_id',
                        'terms'     => $term,
                        'operator'  => 'IN',
                    ]
                ]
            ]; 
        }
        $query = new \WP_Query( $arg );
        ?>
        <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
                <div class="post-slider-items row">
                    <?php 
                    if (!$query->have_posts()): ?>

                        <div class="alert alert-warning">
                            <?php   echo 'Sorry, no results were found.';  ?>
                        </div>

                        <?php  echo get_search_form(false); ?>
                        
                    <?php endif; ?>
                    
                    <?php while ($query->have_posts()):
                    
                         $query->the_post() ?>
                        <div id = "post_item" class="post-item vc-col-<?php echo $vc_column; ?>"> 
                            <article <?php echo post_class() ?> >
                                <header>
                                    <h3 class="entry-title">
                                        <a href="<?php echo esc_url(get_permalink()) ; ?>"><?php echo  get_the_title(); ?></a>
                                    </h3>
                                    <time class="updated" datetime="<?php echo get_post_time('c', true) ?>"><?php echo get_the_date() ?></time>
                                    <p class="byline author vcard">
                                      <?php  __('By', 'vicoders') ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>" rel="author" class="fn">
                                        <?php echo get_the_author() ?>
                                      </a>
                                    </p>

                                </header>
                            </article>
 
                        </div>

                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
        </div>
<?php     
      
    }
}
