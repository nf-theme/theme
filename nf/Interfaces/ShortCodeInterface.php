<?php

namespace NF\Interfaces;

interface ShortCodeInterface
{
    public function __construct();

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName();

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name);
}
