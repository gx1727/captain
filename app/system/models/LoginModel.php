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

class LoginModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_USER;
        $this->key_id = 'user_id';

        $this->return_status[1] = "";
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
            $user = $this->db->rawQueryOne($sql, array($login, $login, $login, $login));
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
}