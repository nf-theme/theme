namespace App\Widgets;

use MSC\Widget;

class RecentPostTypeWidget extends Widget
{
    public function __construct()
    {
        $widget = [
            'id'          => __('recent_post_type', 'vicoders'),
            'label'       => __('&#9782; Vicoders &#187; Recent Post Type', 'vicoders'),
            'description' => __('Get a list of recent custom post type', 'vicoders'),
        ];

        $fields = [
            [
                'label' => __('Select Layout', 'vicoders'),
                'name'  => 'layout',
                'type'  => 'select',
                'options' => [
                	'list' => 'List',
                	'thumbnail' => 'Thumbnail',
                ]
            ],
            [
                'label' => __('Select Category', 'vicoders'),
                'name'  => 'category',
                'type'  => 'select',
                'options' => $this->getCategories()
            ],
            [
                'label' => __('Number Posts', 'vicoders'),
                'name'  => 'number_posts',
                'type'  => 'number',
            ],
            [
                'label' => __('Select Post Type', 'vicoders'),
                'name'  => 'post_type',
                'type'  => 'select',
                'options' => [
                    'post' => 'post',
                ]
            ],
        ];
        parent::__construct($widget, $fields);
    }

    public function getCategories()
    {
		$categories = get_categories(array(
			'orderby' => 'name',
		));

		$cates[0] = __('Select Category', 'vicoders');

		foreach ($categories as $key => $value) {
			$cates[$value->term_id] = $value->name;
		}

		return $cates;
    }

    public function handle($instance)
    {
        // var_dump($instance);
        $data = [
            'post_type' => $instance['post_type'],
        	'layout' => $instance['layout'],
        	'category' => $instance['category'],
        	'number_posts' => $instance['number_posts']
        ];

        view('partials.widgets.widget-recent-post-type', $data);
	}
}