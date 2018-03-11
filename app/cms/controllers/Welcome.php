<?php

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-03-11
 * Time: 下午 8:34
 */

use \captain\core\Controller;

class Welcome extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');

        $this->return_status[1] = '失败';
    }

    public function index()
    {
        $test = $this->input->get_post('test', '');
        echo $test;
//        $this->view('welcome_index');
    }

    public function top()
    {
        echo "welcome_top";
    }
}