<?php

namespace App\Blocks;

use App\Blocks\GutenburgBlock;

class ContactBlock extends GutenburgBlock
{
    public $name = 'vc_contact_form';
    public $title = 'VC Contact Form';
    public $description = 'VC Contact Form Gutenburg Block';
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
        // Create id attribute allowing for custom "anchor" value.
		$id = 'contact-block-' . $block['id'];
		if( !empty($block['anchor']) ) {
		    $id = $block['anchor'];
		}

		// Create class attribute allowing for custom "className" and "align" values.
		$className = 'contact-block';
		if( !empty($block['className']) ) {
		    $className .= ' ' . $block['className'];
		}
		if( !empty($block['align']) ) {
		    $className .= ' align' . $block['align'];
		}
		if( $is_preview ) {
		    $className .= ' is-admin';
		}

		$contact_title_text = get_field('contact_title_text');
		$contact_image = get_field('contact_image');
		$contact_background_color = get_field('contact_background_color');
		$name_contact_form = get_field('name_contact_form');

		?>
		<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" style="background-image: url(<?php echo $contact_image; ?>);">
			<div class="bg-image" style="background-color: <?php echo $contact_background_color; ?>"></div>
			<div class="container">
				<div class="row">
					<div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-sm-12">
						<div class="custom-title">
							<h3 class="title"><?php echo $contact_title_text; ?></h3>
						</div>
						<div class="form-contact">
							<?php echo do_shortcode("[nf_contact_form name='".$name_contact_form."']"); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php     
      
    }
}

