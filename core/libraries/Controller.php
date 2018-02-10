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
    var $log;
    var $router;
    var $input;

    function __construct()
    {
        global $captain_log,
               $captain_router,
               $captain_input;
        $this->log = &$captain_log;
        $this->router = &$captain_router;
        $this->input =  &$captain_input;
    }

    /**
     * 写日志
     */
    public function log()
    {
        $args = func_get_args();
        call_user_func_array(array(&$this->log, 'log'), $args);
    }
}