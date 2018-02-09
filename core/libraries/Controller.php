<?php
namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:56
 */

namespace captain\core;


class Controller
{
    var $router;
    function __construct()
    {
        global $router;
        $this->router = &$router;
    }
}