<?php

namespace NF\Interfaces;

/**
 * create an interface for custom post type
 *
 * @author Duy Nguyen
 * @since  1.0.0
 */
interface CustomPostInterface
{
    public function __construct();

    public function getArgs();

    public function registerPostType();
}
