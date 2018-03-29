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

    public function enter()
    {
        $this->model('\captain\system\LoginModel', 'loginMod');
        $username = $this->input->get_post('username');
        $pwd = $this->input->get_post('pwd');

        if (isset($username) && $username
            && isset($pwd) && $pwd) {
            $user_ret = $this->loginMod->username_pwd($username, $pwd);
            if ($user_ret->get_code() === 0) {
                $user_ret->set_result($user_ret->get_data());
                $this->json($this->get_result($user_ret));
            } else {
                $this->json($this->get_result($user_ret));
            }
        }
    }

    public function entrance()
    {
        if (method_exists($this, $this->input->_uri)) {
            return call_user_func_array(array($this, $this->input->_uri), array());
        } else {
            // 不是默认对应的function
            show_404();
        }
    }
}