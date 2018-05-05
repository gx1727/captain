<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-05-04
 * Time: 下午 8:40
 */

namespace captain\tools;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Tools extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'tools');

        $this->return_status[1] = '';
    }

    public function index()
    {
        $this->view('index');
    }

    /**
     * 时间工具
     */
    public function time()
    {
        echo 'time';
    }
}