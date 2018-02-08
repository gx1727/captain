<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:54
 */

namespace captain\system;


use \captain\core\Controller;


class Index extends Controller
{
    public function index()
    {
        global $test;
        echo 'hello world';
        $v = "123";
        include(BASEPATH . 'app/system/views/index.php');
    }
}