<?php

namespace App\Taxonomies;

use App\Taxonomies\BaseTaxonomy;

class SampleTaxonomy extends BaseTaxonomy
{
    public function __construct()
    {
        $config = [
            'slug'   => 'sample_tax',
            'single' => 'Sample Tax',
            'plural' => 'Sample Tax',
        ];

        $postType = 'post';

        $args = [

        ];

        parent::__construct($config, $postType, $args);
    }
}
