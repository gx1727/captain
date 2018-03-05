<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 10:10
 */

use \captain\core\Model;

class UserModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_USER;
        $this->key_id = 'user_id';

        $this->model('\captain\system\LoginModel', 'loginMod');

        $this->return_status[1] = "没有找到符合条件的数据, 用户不存在";
        $this->return_status[2] = "用户登陆名已存在，注册新用户失败";
    }

    public function test()
    {
        $this->database();
    }

    /**
     * 需求用户数据
     * @param $user_code
     * @return bool
     */
    public function get_user($user_code)
    {
        $user = $this->get($user_code, $this->table_name, 'user_code');
        if ($user) {
            $user['weixin'] = $this->get($user_code, CAPTAIN_USERWEIXIN, 'user_code');
        }
        return $user;
    }

    private function add_user($user_name, $user_true_name, $user_phone, $user_email, $user_qq, $ub_photo, $user_pwd, $user_wxopenid = '')
    {
        $this->load->library('XXCode');
        $user_code = $this->xxcode->get_code("USERCODE", NULL);
        $userData = array(
            'user_code' => $user_code,
            'user_name' => $user_name,
            'user_true_name' => $user_true_name,
            'user_phone' => $user_phone,
            'user_email' => $user_email,
            'user_qq' => $user_qq,
            'user_wxopenid' => $user_wxopenid,
            'user_photo' => $ub_photo,
            'user_pwd' => md5($user_code . $user_pwd),
            'user_atime' => time(),
            'user_status' => 0
        );
        $this->add($userData, XS_USER);
        return $user_code;
    }

    public function register_user($user_name, $user_phone, $user_email, $user_wxopenid, $user_photo, $user_role, $user_pwd = false)
    {
        $ret = new Ret($this->return_status);
        if ($this->loginMod->get_user($user_name)) { //用户已存在
            $ret->set_code(2);
        } else {
            if ($user_pwd) {
//                $user_code = $this->add_user($user_name, $user_true_name, $user_phone, $user_email, $user_qq, $ub_photo, $user_pwd);
//                $this->_add_userrole($user_code, $ur_role_code);
//                $ret->set_data($user_code);
            } else {
                $ret->set_code(8);
            }
        }
        return $ret;
    }

    /**
     * 获取用户列表
     * @param $page_page
     * @param $page_pagesize
     * @param bool $page_order
     * @param bool $page_type
     * @param bool $keyword
     * @return \captain\core\Ret
     */
    public function get_user_list($page_page, $page_pagesize, $page_order = false, $page_type = false, $keyword = false)
    {
        $param_array = array();
        $sql = "from " . CAPTAIN_USER . " where user_status = 0";
        if ($keyword !== false) {
            $sql .= ' and (user_code like ?';
            $sql .= ' or user_name like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($page_page - 1) * $page_pagesize;
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$page_pagesize, $page_order, $page_type);
    }


}