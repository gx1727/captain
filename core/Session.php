<?php
/**
 * session类
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-27
 * Time: 下午 7:49
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
    var $_user_data = array();

    function __construct($config)
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->_config = $config;
        $this->_ip_address = $_SERVER['REMOTE_ADDR'];

        $this->table_name = $config['sess_save_path'];
        $this->key_id = 'session_id';

        $this->database(); // 初始化数据库连接
        $this->start(); // 初始化session数据

    }

    private function start()
    {
        if (isset($_COOKIE[$this->_config['sess_cookie_name']])) {
            $this->_session_id = $_COOKIE[$this->_config['sess_cookie_name']];
            $ret = $this->get($this->_session_id);
            if ($ret) { // session已存在
                $this->_ip_address = $ret['ip_address'];
                $this->_create_time = $ret['create_time'];
                $this->_user_data = json_decode($ret['user_data'], true);

                $this->update();
            } else {
                $this->_session_id = '';
            }
        }
        if (!$this->_session_id) {
            // 创建seesion_id, 33位到39位不定
            $this->_session_id = md5($this->_ip_address . $this->_create_time . Rand::getRandChar(40))
                . Rand::createRand((1 + Rand::randNum(7)), '0123456789abcdefghijklmnopqrstuvwxyz');


            $this->_create_time = time();
            $this->_last_activity = time();
            $session_data = array(
                'session_id' => $this->_session_id,
                'ip_address' => $this->_ip_address,
                'create_time' => $this->_create_time,
                'last_activity' => $this->_last_activity,
                'user_data' => json_encode($this->_user_data)
            );
            $this->add($session_data);

            setcookie(
                $this->_config['sess_cookie_name'],
                $this->_session_id,
                time() + $this->_config['sess_expiration'],
                $this->_config['cookie_path'],
                $this->_config['cookie_domain']
            );
        }
    }

    /**
     * 更新session失效时间
     */
    private function update()
    {
        $this->_last_activity = time();
        $session_data = array(
            'last_activity' => $this->_last_activity,
            'user_data' => json_encode($this->_user_data)
        );
        $this->edit($this->_session_id, $session_data);
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

    public function gc()
    {
        $sql = 'delete from ' . $this->_config['sess_save_path'] . ' where last_activity < ' . (time() - $this->_config['sess_expiration']);
        $this->db->query($sql);
    }
}