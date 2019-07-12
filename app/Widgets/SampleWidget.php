<?php

namespace App\Widgets;

use App\Widgets\BaseWidget;

/**
 *
 */
class SampleWidget extends BaseWidget
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
