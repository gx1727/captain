<?php
/**
 * session类
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-27
 * Time: 下午 7:49
 *
 */

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

class Session extends Base
{
    var $_config;
    var $_session_id = null;
    var $_ip_address = null;
    var $_create_time = 0;
    var $_last_activity = 0;
    var $_cookie_activity = 0;
    var $_user_data = array();
    var $_ctime;

    function __construct($config)
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->_config = $config;
        $this->_ip_address = $_SERVER['REMOTE_ADDR'];

        $this->table_name = $config['sess_save_path'];
        $this->key_id = 'session_id';

        $this->_ctime = time();

        $this->database(); // 初始化数据库连接
        $this->start(); // 初始化session数据

    }

    private function start()
    {
        if (isset($_COOKIE[$this->_config['sess_cookie_name']])) {
            $this->_session_id = $_COOKIE[$this->_config['sess_cookie_name']];
            $session = $this->get($this->_session_id);
            if ($session && $session['last_activity'] >= $this->_ctime) { // session已存在 且有效
                $this->_ip_address = $session['ip_address'];
                $this->_create_time = $session['create_time'];
                $this->_cookie_activity = $session['cookie_activity'];
                $this->_user_data = json_decode($session['user_data'], true);
                $this->update(false);
            } else {
                $this->_session_id = '';
            }
        }
        if (!$this->_session_id) { // 还没有seesion,新建
            $this->refresh_cookie();
        }
    }

    /**
     * 刷新 cookie
     */
    private function refresh_cookie()
    {
        // 创建seesion_id, 33位到39位不定
        $this->_session_id = md5($this->_ip_address . $this->_create_time . Rand::getRandChar(40))
            . Rand::createRand((1 + Rand::randNum(7)), '0123456789abcdefghijklmnopqrstuvwxyz');

        // 设置cookie有效期为浏览器session，不限时间
        setcookie(
            $this->_config['sess_cookie_name'],
            $this->_session_id
            , 0 //
//            ,$this->_ctime + $this->_config['sess_expiration']
            , $this->_config['cookie_path']
            , $this->_config['cookie_domain']
        );

        $this->_create_time = $this->_ctime;
        $this->_last_activity = $this->_ctime + $this->_config['sess_expiration'];
        $this->_cookie_activity = $this->_ctime + $this->_config['cookie_expiration'];
        $session_data = array(
            'session_id' => $this->_session_id,
            'ip_address' => $this->_ip_address,
            'create_time' => $this->_create_time,
            'last_activity' => $this->_last_activity,
            'cookie_activity' => $this->_cookie_activity,
            'user_data' => json_encode($this->_user_data)
        );
        $this->add($session_data);
    }

    /**
     * 更新session失效时间
     * @param bool $user_data_flag 是否有数据更新
     */
    private function update($user_data_flag = true)
    {
        $c_session_id = $this->_session_id; //当前session_id, 可能被刷新掉

        $this->_last_activity = $this->_ctime + $this->_config['sess_expiration'];
        $session_data = array(
            'last_activity' => $this->_last_activity,
        );
        if ($user_data_flag) {
            $session_data['user_data'] = json_encode($this->_user_data);
        }

        // 判断是否有必要刷新cookie值 即 session_id
        if ($this->_cookie_activity < $this->_ctime) {
            $this->refresh_cookie();
            $session_data['last_activity'] = $this->_ctime + 10; // 10秒的缓冲期，在 这10内，老session是有效
        }

        $this->edit($c_session_id, $session_data); // $c_session_id 这时有可能是老的session_id
    }

    /**
     * 获取session值
     * @param $k
     * @return mixed|null
     */
    public function get_sess($k)
    {
        return (isset($this->_user_data[$k])) ? $this->_user_data[$k] : null;
    }

    public function set_sess($k, $v)
    {
        $this->_user_data[$k] = $v;
        $this->update();
    }

    public function del_sess($k)
    {
        if (isset($this->_user_data[$k])) {
            unset($this->_user_data[$k]);
            $this->update();
        }
    }

    public function destroy()
    {
        $this->del($this->_session_id);
    }

    /**
     * 删除无用的sesseion
     */
    public function gc()
    {
        $sql = 'delete from ' . $this->_config['sess_save_path'] . ' where last_activity < ' . $this->_ctime;
        $this->db->query($sql);
    }
}