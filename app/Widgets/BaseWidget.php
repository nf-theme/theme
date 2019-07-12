<?php
/**
 * create a widget
 *
 * @since      1.0.0
 * @package    msc
 * @subpackage widget
 * @author     Monkeyscode <monkeyscodestudio@gmail.com>
 */

namespace App\Widgets;

abstract class BaseWidget extends \WP_Widget
{
    /**
     * @var   array
     * @since 1.0.0
     */
    public $fields = [];

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

		if (!is_array($widget) || empty($widget)) {
		    wp_die(__('The first param is invalid', 'monkeyscode'));
		}

		if (!isset($widget['id']) || $widget['id'] == '') {
			wp_die(__('Widget Param invalid', 'monkeyscode'));
		}

		if (!is_array($fields) || empty($fields)) {
		    wp_die(__('The second param is invalid', 'monkeyscode'));
		}

		$this->widget = $widget;

		$this->fields = $fields;
		
		parent::__construct(
			$this->widget['id'],
			__($this->widget['label'], 'monkeyscode'),
			['description' => __($this->widget['description'], 'monkeyscode')]
		);

		add_action('widgets_init', [$this, 'widgetInit']);
		add_action('admin_enqueue_scripts', [$this, 'scripts']);

	}

	/**
	 * register necessary scripts for widget
	 * 
	 * @since 1.1.3
	 */
	public function scripts()
	{
		wp_enqueue_script('msc-widget-script', $this->getScriptUrl(), ['jquery'], '', true);
	}

	/**
	 * get script.js url
	 * 
	 * @since  1.1.3
	 * @return string
	 */
	public function getScriptUrl()
	{
		$documentRoot = $_SERVER['DOCUMENT_ROOT'];
		$serverName = $_SERVER['HTTP_HOST'];
		$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$path = __DIR__;
		$path = str_replace('\\', '/', $path);
		$url = str_replace($documentRoot, $serverName, $path);
		return $protocol . $url . '/script.js';
	}

	/**
	 * register widget
	 *
	 * @since  1.1.0
	 * @return void
	 */
	public function widgetInit()
	{
		register_widget($this);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see    WP_Widget::widget()
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

			if (!isset($field['name']) || $field['name'] == '') {
				wp_die(__('The field must have a name', 'monkeyscode'));
			}

			if (!isset($field['type']) || $field['type'] == '') {
				wp_die(__('The field must have a type', 'monkeyscode'));
			}

			if (!isset($instance[$field['name']])) $instance[$field['name']] = '';

			$this->checkTypeOfField($field, $instance[$field['name']]);
		}
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
            case 'multiple_select':
                $this->renderMultipleSelectField($field, $instance);
                break;
            default:
            	// do something here
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
	 * render multiple select field
	 *
	 * @since  1.1.0
	 *
	 * @param  array $field
	 * @param  array $instance
	 * @return void
	 */
	public function renderMultipleSelectField($field, $instance)
	{
		//var_dump($instance);
		?>
		<?php if (!empty($field['options'])) : ?>
		<p>

		<label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
			<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
		</label>

		<select multiple class="widefat"
				id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>[]">

			<?php foreach ($field['options'] as $key => $option) : ?>
				<option value="<?php echo $key; ?>" <?php selected(true, in_array($option, $instance)); ?>>
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
    		<label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>">
    			<?php esc_attr_e($field['label'] . ':', 'text_domain'); ?>
    		</label>
            <input class="widefat msc-upload-input"
                    type="hidden"
                    name="<?php echo esc_attr( $this->get_field_name($field['name']) ); ?>"
                    value="<?php echo esc_attr($instance); ?>" />
			<div class="media-widget-control">
				<div class="media-widget-preview">
					<div class="attachment-media-view">
						<?php if ($instance == '') : ?>
							<div class="placeholder"><?php _e('No image selected') ?></div>
						<?php else : ?>
							<img class="attachment-thumb" src="<?php echo $instance; ?>" alt="" />
						<?php endif; ?>
					</div>
				</div>
			</div>

            <button
                class="button select-media msc-upload-btn"
                id="<?php echo esc_attr( $this->get_field_id($field['name']) ); ?>"
                type="button"
                name="button">
                <?php _e('Add Image', 'monkeyscode'); ?>
            </button>
            <p></p>
		<?php
    }

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see    WP_Widget::update()
	 * @since  1.0.0
	 * @param  array $new_instance Values just sent to be saved.
	 * @param  array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = [];

		foreach ($this->fields as $field) {

			switch ($field['type']) {
				case 'text':
					$instance[$field['name']] = strip_tags($new_instance[$field['name']]);
					break;
				case 'number':
					$instance[$field['name']] = intval($new_instance[$field['name']]);
					break;
				default:
					$instance[$field['name']] = $new_instance[$field['name']];
					break;
			}
		}

		//var_dump($instance);

		return $instance;
	}
}
