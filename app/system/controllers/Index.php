<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:54
 *
 * $this->model('\captain\system\UserModel', 'userMod', 'system');
 */
use \captain\core\Controller;
use \captain\core\Rand;

class Index extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->return_status[10] = '不服来战';
    }

    public function index()
    {
        $this->library_core('\captain\core\Pinyin', 'pinyinLib');
        echo $this->pinyinLib->py('不服来战');
        exit;
        var_dump($this->get_session());
//        $this->library('\captain\core\Secret', 'secretLib');
//        print_r($this->secretLib->decoding('kchaA1FJkPwySVJRPhWCNLxwZsai6O4z479s7vrCLMHJS9kan6+l+AQS0Ut8Be94CnjhYnsYBz7yUTV4C3YqfZVL0qpaTDgbZ7oZ2eSEP40em9Up7R39jEWrXCKYqVonZZr+sY5BilfirJ230mP7FERSWU3U4gXhL5fqdxU4BRq/+OCPJj6AmjauPd/J/OBMVoAuqEHuZ9FjDuisk1BsJ0YePUrdwqEZAzTzsqJFKOEE3uwmyIx7GQLs82mLg37dtOWL+Weauxr1CKT31wz/Sa2/kTBCSgDvlJ7mMg7vXOQYR5UGWiJcfi5nUCG+Jr7SkfPn7dxe2qx2TItS2I2cuQ=='));
//        exit;

        $this->view('index');
        exit;

        $this->library('\captain\core\code', 'codeLib');
//        $ret = $this->codeLib->get_code('USERCODE2', 2);
//        echo $ret;
//        echo "<p/>============<p/>";
//        exit;
//
//         $code = Rand::encodeCode('21002100120', 24);
//        echo "<p/>============<p/>";
        echo Rand::decodeCode('435945833903035000502262');
        echo "<p/>============<p/>";
        exit;
        $this->model('\captain\system\AccountModel', 'accountMod');
//        $ret = $this->accountMod->refresh_account('rmb', 1);
        $ret = $this->accountMod->manage_account('rmb', 'U0000000002', 5453, AC_CREBIT_ADJUSTMENT_DECREASE);
        $ret = $this->accountMod->get_accountloglist('rmb', 1, 1, 99999);
        print_r($ret->get_data());
        exit;


        $this->model('\captain\system\UserModel', 'userMod', 'system');
        $ret = $this->userMod->register_user('gx1727', '', '', '');
        print_r($ret);
        exit;

        echo "<hr/>";
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
        $v = $this->input->get_uri();
        print_r($v);
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
//
//        $captain_router->forward();
    }

    public function hello_tag()
    {
        $v = $this->input->get_uri();
        print_r($v);
    }

    public function api()
    {
        echo 'api';
        print_r($this->get_session());
        exit;
    }
}