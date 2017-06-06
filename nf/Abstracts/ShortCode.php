<?php

namespace NF\Abstracts;

use NF\Interfaces\ShortCodeInterface;

class ShortCode implements ShortCodeInterface
{
    public function __construct()
    {
        if (function_exists('add_shortcode')) {
            add_shortcode($this->name, [$this, 'render']);
        }
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
