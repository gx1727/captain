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
use \captain\core\Ret;

class UserModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_USER;
        $this->key_id = 'user_id';

        $this->return_status[1] = "没有找到符合条件的数据, 用户不存在";
        $this->return_status[2] = "用户已存在，注册新用户失败";
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

    /**
     * @param $user_name
     * @param $user_title
     * @param $user_phone
     * @param $user_email
     * @param $user_wxopenid
     * @param $user_pwd
     * @return mixed
     */
    private function add_user($user_name, $user_title, $user_phone, $user_email, $user_wxopenid, $user_pwd, $user_photo = '')
    {
        $this->library_core('\captain\core\code', 'codeLib');
        $user_code = $this->codeLib->get_code('USERCODE');
        $userData = array(
            'user_code' => $user_code,
            'user_name' => $user_name,
            'user_title' => $user_title, // 用户显示名
            'user_phone' => $user_phone,
            'user_email' => $user_email,
            'user_wxopenid' => $user_wxopenid,
            'user_photo' => $user_photo,
            'user_pwd' => $user_pwd ? $this->loginMod->get_userpwd($user_code, $user_pwd) : '',
            'user_atime' => time(),
            'user_status' => 0
        );
        $this->add($userData, $this->table_name);
        return $user_code;
    }

    private function add_userrole($user_code, $ur_role_code = DEFINE_ROLE)
    {
        $userroleData = array(
            'user_code' => $user_code,
            'ur_failure_time' => 0,
            'ur_role_code' => $ur_role_code,
            'ur_bench_role' => DEFINE_ROLE,
            'ur_atime' => time()
        );

        $this->add($userroleData, CAPTAIN_USERROLE);
    }

    /**
     * 一般注册新用户
     * @param $user_name
     * @param $user_phone
     * @param $user_email
     * @param $user_wxopenid
     * @param bool $user_pwd
     * @param string $user_role 默认 ROLE00004
     * @return Ret
     */
    public function register_user($user_name, $user_phone, $user_email, $user_wxopenid, $user_pwd = false, $user_role = 'ROLE00004')
    {
        $this->model('\captain\system\LoginModel', 'loginMod');

        $ret = new Ret($this->return_status);
        if ($this->loginMod->get_user($user_name)) { //用户已存在
            $ret->set_code(2);
        } else {
            $user_code = $this->add_user($user_name, $user_name, $user_phone, $user_email, $user_wxopenid, $user_pwd);
            $this->add_userrole($user_code, $user_role);
            $ret->set_data($user_code);
        }
        return $ret;
    }

    public function register_weixin($weixin_data, $user_role = 'ROLE00004')
    {
        $this->model('\captain\system\LoginModel', 'loginMod');

        $ret = new Ret($this->return_status);
        if ($this->loginMod->get_user($weixin_data['openid'])) { //用户已存在
            $ret->set_code(2);
        } else {
            $user_code = $this->add_user('', $weixin_data['nickname'], '', '', $weixin_data['openid'], '', $weixin_data['headimgurl']);
            $this->add_userrole($user_code, $user_role);
            $weixin_data['user_code'] = $user_code;
            $weixin_data['uw_atime'] = time();
            $weixin_data['uw_status'] = 0;
            $this->add($weixin_data, CAPTAIN_USERWEIXIN);
            $ret->set_data($user_code);
            $ret->set_code(0);
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