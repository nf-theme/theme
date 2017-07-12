<?php

namespace App\Widgets;

use NF\Abstracts\Widget;

/**
 *
 */
class SampleWidget extends Widget
{
    public function __construct()
    {
        $widget = [
            'id'          => __('sample_widget', 'textdomain'),
            'label'       => __('Sample Widget', 'textdomain'),
            'description' => __('This is a sample widget', 'textdomain'),
        ];

        $fields = [
            [
                'label' => __('Title', 'textdomain'),
                'name'  => 'test_field',
                'type'  => 'text',
            ],
        ];
        parent::__construct($widget, $fields);
    }

    public function handle($instance)
    {
        var_dump($instance);
	}
}
