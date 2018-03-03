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
//        echo "index";
        $this->library('\captain\core\Secret', 'secretLib');

//        $this->codeLib->refresh_code('URLCODE');
        print_r( $this->secretLib->decoding('0753caaf934ad4758cee7ec8bf021001bd51346161eb65eaf222b2ade187471f8d7956197d0330331d5df209f8efca2f0960f509c7abe561ee899b5cda2e94f3bda318151eef502da29ace9748d4d80be183d2daf06ccb1a854c96a6683205728273be0a3fe55e2c9ddf1fa2136c18a68ba7ab1a1120f7089c4ed9589f366b1c'))    ;
//        exit;
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
        print_r($this->session);
        exit;
    }
}