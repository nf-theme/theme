namespace App\Widgets;

use App\Widgets\BaseWidget;

class SearchWidget extends BaseWidget
{
    public function __construct()
    {
        $widget = [
            'id'          => __('vcd_search_widget', 'vicoders'),
            'label'       => __('&#9782; Vicoders &#187; Search', 'vicoders'),
            'description' => __('Search Form', 'vicoders'),
        ];

        $fields = [
            [
                'label' => __('Placeholder', 'vicoders'),
                'name'  => 'placeholder',
                'type'  => 'text',
            ],
        ];

        parent::__construct($widget, $fields);
    }

    public function handle($instance)
    {
        // var_dump($instance);
        $data = [
            'placeholder' => $instance['placeholder'],
        ];

        view('partials.search-form-{{ $layout }}', $data);
    }
}
