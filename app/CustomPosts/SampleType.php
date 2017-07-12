<?php
/**
 * Sample class for a custom post type
 * 
 */

namespace App\CustomPosts;

use NF\Abstracts\CustomPost;

class SampleType extends CustomPost
{
    /**
     * [$type description]
     * @var string
     */
    public $type = 'sample';

    /**
     * [$single description]
     * @var string
     */
    public $single = 'Sample';

    /**
     * [$plural description]
     * @var string
     */
    public $plural = 'Samples';

    /**
     * $args optional
     * @var array
     */
    //public $args = ['menu_icon' => 'dashicons-location-alt'];

}
