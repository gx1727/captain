<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/2/9
 * Time: 16:48
 */
class Input
{
    var $_uri;

    function __construct($uri)
    {
        $this->_uri = $uri;
    }

    public function get($key)
    {

    }

    public function post($key)
    {

    }

    public function get_post($key)
    {

    }
}