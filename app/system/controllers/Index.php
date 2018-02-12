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

require_once(BASEPATH . 'app' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'AccountModel.php');

class Index extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->modular = 'system';
    }

    public function index()
    {
        global $captain_db;
        var_dump($captain_db);
        echo '<p/>' . __NAMESPACE__ . '<p/>';
        $this->model('UserModel', 'userMod', 'system');
        $this->userMod->test();

//        $accountMod = new AccountModel();
//        $accountMod->test();

        // include(BASEPATH . 'app/system/views/index.php');
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