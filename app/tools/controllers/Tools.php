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
        $op = $this->input->get_post('op');
        if($op == 'get_time') {
            $this->json($this->get_result(array('data' => time())));
        } else if($op == 'format_time') {
            $time = $this->input->get_post('time');
            $this->json($this->get_result(array('data' => date('Y-m-d H:i:s', $time))));
        } else if($op == 'create_time') {
            $this->json($this->get_result(array('data' =>  mktime($_POST['hour'], $_POST['minute'], $_POST['second'], $_POST['mouth'], $_POST['day'], $_POST['year']))));
        }

    }
}