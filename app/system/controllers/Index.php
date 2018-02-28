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
        parent::__construct(__NAMESPACE__, 'system');

        $this->return_status[10] = '不服来战';
    }

    public function index()
    {
        echo "index";

        echo $this->input->get_post('hello');
        $this->model('\captain\system\UserModel', 'userMod', 'system');
        $user_list = $this->userMod->get_user_list(1, 10, false, false);
        $this->assign('hello', 'world');
        $this->assign('user_list', $user_list->get_data(2));

        $this->help('view_helper'); // 引入 view_helper

        $this->view('index');

//        $user = $this->userMod->get_all(3);
//        for ($i = 1; $i < 20; $i++) {
//            $this->userMod->add(array('user_code' => '000' . $i));
//        }

//        var_dump($this->userMod->edit('eeee', array('user_name' => 'gx1727'), CAPTAIN_USER, 'user_code'));
//        var_dump($this->userMod->del('U0000000005', CAPTAIN_USER, 'user_code'));
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
        echo 'def';
        $v = $this->input->get_uri();
        print_r($v);
    }

    public function hello_art()
    {
        echo "hello_art";
        exit;
        global $captain_router;

        $captain_router->redirection();
    }

    public function hello_tag()
    {
        $v = $this->input->get_uri();
        $this->json($v);
    }

    public function api()
    {
        echo 'api';
        print_r($this->session);
        exit;
    }
}