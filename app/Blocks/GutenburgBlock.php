<?php

namespace App\Blocks;

class GutenburgBlock implements GutenburgBlockInterface
{
    public $name = '';
    
    public function getName()
    {
        return $this->name;
    }

    public function render($block)
    {
        return '';
    }

}
