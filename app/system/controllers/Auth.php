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

class Auth extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->model('\captain\system\AuthModel', 'authMod');
        $this->return_status[1] = '';
        $this->return_status[998] = '没有权限';
    }

    public function role_list()
    {
        $keyword = $this->input->get_post('keyword');
        $role_list_ret = $this->authMod->get_role_list($this->get_list_param(), $keyword);

        if ($role_list_ret->get_code() === 0) {
            $role_list = $role_list_ret->get_data();
            $this->json($this->get_result(array('data' => $role_list, 'total' => $role_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }

    public function role_edit()
    {
        $role_id = $this->input->get_post('role_id');
        $post_data = $this->input->get_post('postData');
        $post_data = json_decode($post_data, true);
        $ret = $this->authMod->role_edit($role_id, $post_data);
        $this->json($this->get_result($ret));
    }
}