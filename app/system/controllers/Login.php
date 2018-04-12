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
        $this->return_status[997] = '权限不足';
        $this->return_status[998] = '没有登陆';
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

    /**
     * 没有权限
     */
    public function reject()
    {
        $session = &$this->get_session(); // 引用 session
        $user_login_status = array(
            'user_code' => $session->get_sess('user_code'),
            'login_time' => $session->get_sess('login_time'), // 登录时间
            'role_name' => $session->get_sess('role_name'),
        );
        $user_login_status['code']  = $user_login_status['user_code'] ? 997 : 998;
        $this->json($this->get_result($user_login_status, $user_login_status['code'] ));
    }
}