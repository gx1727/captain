<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-03-05
 * Time: 下午 7:44
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class LoginModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_USER;
        $this->key_id = 'user_id';

        $this->return_status[1] = "用户不存在或密码不正确";
        $this->return_status[2] = "用户不存在或密码不匹配";
    }

    /**
     * 通过登录名查找
     * @param $login
     * @return Ret
     */
    public function get_user($login)
    {
        $user = false;
        if ($login) {
            $sql = 'SELECT * FROM ' . $this->table_name . ' u WHERE (user_name = ? or user_phone = ? or user_email = ? or user_wxopenid = ?) AND user_status = 0 limit 1';
            $user = $this->query($sql, array($login, $login, $login, $login));
        }

        return $user;
    }

    /**
     * @param $user_code
     * @param $pwd md5(用户输入明文) 后 再倒序
     * @return string
     */
    public function get_userpwd($user_code, $pwd)
    {
        if ($pwd) {
            return md5($user_code . $pwd);
        } else {
            return '';
        }
    }

    public function username_pwd($username, $pwd)
    {
        $this->library_core('\captain\core\Secret', 'secretLib');
        $pwd = $this->secretLib->decoding($pwd);

        $ret = new Ret($this->return_status);
        $user = $this->get_user($username);
        if ($user) {
            if ($user['user_pwd'] == $this->get_userpwd($user['user_code'], $pwd)) {
                unset($user['user_pwd']);
                $user['role'] = $this->get_userrole($user['user_code']);
                $this->login($user); // 登录的操作
                $ret->set_data($user);
            } else {
                $ret->set_code(2); // 用户不存在
            }
        } else {
            $ret->set_code(1); // 用户不存在
        }
        return $ret;
    }

    /**
     * 登录的操作
     * @param $user
     */
    public function login($user)
    {
        $this->log('用户登录:[' . $user['user_code'] . '] ' . $user['user_name']);
        $session = &$this->get_session(); // 引用 session
        $session->set_sess('user_code', $user['user_code']);
        $session->set_sess('login_time', time()); // 登录时间
        $session->set_sess('role_name', $user['role']['role_name']);
    }

    /**
     * 获取用户角色编码
     * 判断角色是否到期，如到期，需要换到后备角色
     * @param $user_code
     * @return string 角色编码
     */
    public function get_userrole($user_code)
    {
        $user_role = DEFINE_ROLE; // 默认

        $sql = "SELECT * FROM " . CAPTAIN_USERROLE . " WHERE user_code = ? AND ur_status = 0";
        $role = $this->query($sql, array($user_code));
        if ($role) {
            if ($role['ur_failure_time'] > 0 && $role['ur_failure_time'] < time()) {
                $user_role = $role['ur_bench_role']; // 角色已过期 自动使用后备角色
                $this->edit($role['ur_id'], array('ur_status' => 1), CAPTAIN_USERROLE, 'ur_id');
                $data = array(
                    'user_code' => $user_code,
                    'ur_role_code' => $role['ur_bench_role'],
                    'ur_failure_time' => 0,
                    'ur_bench_role' => DEFINE_ROLE,
                    'ur_remark' => '',
                    'ur_atime' => time(),
                    'ur_status' => 0
                );
                $this->db->add($data, CAPTAIN_USERROLE);
            } else {
                // 角色还在有效期内
                $user_role = $role['ur_role_code'];
            }
        } else {
            $data = array(
                'user_code' => $user_code,
                'ur_role_code' => DEFINE_ROLE,
                'ur_failure_time' => 0,
                'ur_bench_role' => DEFINE_ROLE,
                'ur_remark' => '',
                'ur_atime' => time(),
                'ur_status' => 0
            );
            $this->db->add($data, CAPTAIN_USERROLE);
        }

        return $this->get($user_role, CAPTAIN_ROLE, 'role_code');
    }
}