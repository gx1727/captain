<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/14
 * Time: 14:57
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Login extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->return_status[1] = '';
    }

    public function login()
    {
        $this->model('\captain\system\LoginModel', 'loginMod');

        $this->input->get_contents();
        $username = $this->input->get_post('username');
        $pwd = $this->input->get_post('pwd');

        if (isset($username) && isset($pwd)) {
            $user_ret = $this->loginMod->username_pwd($username, $pwd);
            if ($user_ret->get_code() === 0) {
                $user_ret->set_result($user_ret->get_data());
                $this->json($this->get_result($user_ret), true);
            } else {
                $this->json($this->get_result($user_ret), true);
            }
        }

//        print_r($user);
    }

    public function entrance()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header('Access-Control-Allow-Headers: x-requested-with,content-type');
            // 什么也不做
            return;
        } else {
            if (method_exists($this, $this->input->_uri)) {
                return call_user_func_array(array($this, $this->input->_uri), array());
            } else {
                // 不是默认对应的function
                show_404();
            }
        }
    }
}