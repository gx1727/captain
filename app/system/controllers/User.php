<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-04-21
 * Time: 下午 7:06
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class User extends Controller
{
    var $user_code;

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->model('\captain\system\UserModel', 'userMod');
        $this->model('\captain\system\LoginModel', 'loginMod');

        $session = &$this->get_session(); // 引用 session
        $this->user_code = $session->get_sess('user_code');
        $this->role_name = $session->get_sess('role_code');

        $this->return_status[1] = '';
        $this->return_status[997] = '权限不足';
        $this->return_status[998] = '没有登陆';
    }

    public function get_user()
    {
        $user = $this->userMod->get_user($this->user_code);
        $this->json($this->get_result($user));
    }

    public function edit_user()
    {
        $user_true_name = $this->input->get_post('user_true_name');
        $user_phone = $this->input->get_post('user_phone');

        $this->userMod->edit_user($this->user_code, array(
            'user_true_name' => $user_true_name,
            'user_phone' => $user_phone
        ));
        $this->json($this->get_result(true));
    }

    public function change_pwd()
    {
        $old_pwd = $this->input->get_post('old_pwd');
        $pwd = $this->input->get_post('pwd');

        $ret = $this->loginMod->change_pwd($this->user_code, $old_pwd, $pwd);
        $this->json($this->get_result($ret));
    }
}