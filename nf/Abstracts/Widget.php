<?php

namespace NF\Abstracts;

/**
 * create a widget
 *
 * @since      1.0.0
 * @package    msc
 * @subpackage widget
 * @author     Monkeyscode <monkeyscodestudio@gmail.com>
 */

abstract class Widget extends \WP_Widget
{
    /**
     * @var   array
     * @since 1.0.0
     */
    public $fields;
    /**
     * @var   array
     * @since 1.0.0
     */
    protected $widget;
	/**
	 * constructor
	 *
	 * @param array $widgets
	 * @param array $fields
	 * @since 1.0.0
	 */
	public function __construct($widget, $fields)
	{
		if (!is_array($widget) || !is_array($fields)) {
		    wp_die(__('It must be an array.', 'monkeyscode'));
		}
		if (empty($widget) || empty($fields)) {
		    wp_die(__('It is not empty.', 'monkeyscode'));
		}
		$this->widget   = $widget;
		$this->fields   = array_reverse(wp_parse_args([[
			'name'  => 'msc_title',
			'type'  => 'text',
			'label' => __('Title', 'msc'),
		]], $fields));
		parent::__construct(
			$this->widget['id'],
			__($this->widget['label'], 'monkeyscode'),
			['description' => __($this->widget['description'], 'monkeyscode')]
		);
		add_action('widgets_init', [$this, 'widgetInit']);
	}
	/**
	 * register widget
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function widgetInit()
	{
		register_widget($this);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param  array $args     Widget arguments.
	 * @param  array $instance Saved values from database.
	 * @return void
	 */
	public function widget($args, $instance)
	{
		echo $args['before_widget'];
		$title = apply_filters( 'widget_title', $instance['msc_title'] );
		if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
		$this->handle($instance);
		echo $args['after_widget'];
	}
	/**
	 * handle data
	 *
	 * @param  array $instance
	 * @since  1.2.1
	 * @return void
	 */
	abstract public function handle($instance);
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param  array $instance Previously saved values from database.
	 * @return void
	 */
	public function form($instance)
	{
		foreach ($this->fields as $field) {
			if (@$instance[$field['name']] == null) $instance[$field['name']] = '';
			$this->checkTypeOfField($field, $instance[$field['name']]);
		}
        $this->scripts();
	}
	/**
	 * check type of the field and navigate it to correct rendering method
	 *
	 * @param array $field
	 * @param array $instance
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function checkTypeOfField($field, $instance)
	{
        switch ($field['type']) {
            case 'text':
                $this->renderTextField($field, $instance);
                break;
            case 'select':
                $this->renderSelectField($field, $instance);
                break;
            case 'checkbox':
                $this->renderCheckboxField($field, $instance);
                break;
            case 'number':
                $this->renderNumberField($field, $instance);
                break;
            case 'textarea':
                $this->renderTextareaField($field, $instance);
                break;
            case 'upload':
                $this->renderUploadField($field, $instance);
                break;
        }
	}
	/**
	 * render input text field
	 *
	 * @since 1.0.0
	 *
	 * @param  array $field
	 * @param  array $instance
	 * @return void
	 */
	public function renderTextField($field, $instance)
	{
		?>
		<p>

		<label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
			<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
		</label>

		<input class="widefat"
				id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>"
				type="text"
				value="<?php echo esc_attr($instance); ?>">

		</p>
		<?php
	}
	/**
	 * render input number field
	 *
	 * @since 1.2.2
	 *
	 * @param  array $field
	 * @param  array $instance
	 * @return void
	 */
	public function renderNumberField($field, $instance)
	{
		?>
		<p>

		<label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
			<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
		</label>

		<input class="widefat"
				id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>"
				type="number"
				value="<?php echo esc_attr($instance); ?>">

		</p>
		<?php
	}
	/**
	 * render a checkbox field
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 * @param array $instance
	 * @return void
	 */
	public function renderCheckboxField($field, $instance)
	{
	    ?>
	    <p>

        <label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
        	<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
        </label>

        <input class="widefat"
        		id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
        		name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>"
        		type="checkbox" value="<?php echo $field['name']; ?>"
        		<?php checked($instance, $field['name']); ?>>

	    </p>
	    <?php
	}
	/**
	 * render select field
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 * @param array $instance
	 * @return void
	 */
	public function renderSelectField($field, $instance)
	{
		?>
		<?php if (!empty($field['options'])) : ?>
		<p>

		<label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
			<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
		</label>

		<select class="widefat"
				id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>">

			<?php foreach ($field['options'] as $key => $option) : ?>
				<option value="<?php echo $key; ?>" <?php selected($instance, (int) $key); ?>>
					<?php echo $option; ?>
				</option>

			<?php endforeach; ?>
		</select>

		</p>
		<?php endif; ?>
		<?php
	}
	/**
	 * render textarea field
	 *
	 * @since  1.0.3
	 *
	 * @param  array $field
	 * @param  array $instance
	 * @return void
	 */
	public function renderTextareaField($field, $instance)
	{
		?>

		<p>

		<label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
			<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
		</label>

		<textarea class="widefat"
					id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>"><?php echo esc_attr($instance); ?></textarea>

		</p>

		<?php
	}
    /**
     * render upload field
     *
     * @since 1.0.6
     *
     * @param  array $field
	 * @param  array $instance
	 * @return void
     */
    public function renderUploadField($field, $instance)
    {
        wp_enqueue_media();
        ?>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
    			<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
    		</label>
            <input class="widefat msc-upload-input"
                    type="hidden"
                    name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>"
                    value="<?php echo esc_attr($instance); ?>" />
            <img class="msc-upload-holder"
                    width=50
                    src="<?php echo esc_attr($instance); ?>"
                    alt="No Image" />
            <button
                class="widefat msc-upload-btn"
                id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
                type="button"
                name="button">
                <?php _e('Upload', 'monkeyscode'); ?>
            </button>
		</p>
		<?php
    }
    /**
     * register script for the fields
     *
     * @return void
     */
    public function scripts()
    {
        ?>
        <script type="text/javascript">
            // upload field
            var $ = jQuery;
            var btnUpload = $('.msc-upload-btn');
            btnUpload.click(function(){
                var $this = $(this);
                var input = $this.siblings('.msc-upload-input');
                var holder = $this.siblings('.msc-upload-holder');
                var url;
                var uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });
                uploader.on('select', function(){
                    attachment = uploader.state().get('selection').first().toJSON();
                    url = attachment.url;
                    holder.attr('src', url);
                    input.attr('value', url);
                });
                uploader.open();
            });
        </script>
        <?php
    }
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		foreach ($this->fields as $field) {
			/**
			 * @TODO validate url value with HTTP Protocol
			 */
            $instance[$field['name']] = (!empty( $new_instance[$field['name']])) ? strip_tags($new_instance[$field['name']]) : '';
		}
		return $instance;
	}
}
