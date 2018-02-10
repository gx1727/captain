<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:54
 */
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

    public function cms()
    {
        echo 'cms';
    }

    public function hello()
    {
        echo 'hello';
    }

    public function def()
    {
        $v = $this->input->get('hello', 'abc', true);
        var_dump($v);

    }

    public function hello_art()
    {
        global $router;

        $router->redirection();
        echo 'hello_art';
    }

    public function hello_tag()
    {
        echo 'hello_tag';
    }

    public function api()
    {
        echo 'api';
    }
}