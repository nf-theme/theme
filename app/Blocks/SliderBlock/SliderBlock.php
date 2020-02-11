<?php

namespace App\Blocks\SliderBlock;

use App\Blocks\GutenburgBlock;

class SliderBlock extends GutenburgBlock
{
    public $name = 'SliderBlock';

    public function __construct()
    {

    }
    public function render()
    {
        return 'slider';
    }
}
