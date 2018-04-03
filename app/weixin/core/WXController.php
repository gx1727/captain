<?php

namespace captain\weixin;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 9:37
 */
use \captain\core\Controller;

class WXController extends Controller
{
    var $openid; // 微信openid
    var $user_code; //
    var $role_name; //
    var $weixin; // 默认微信配制

    var $session; //

    function __construct($namespace, $modular)
    {
        parent::__construct($namespace, $modular);

        $this->weixin = 'alanbeibei';

        $this->return_status[1] = '';

        $this->model('\captain\weixin\WeixinModel', 'weixinMod');

        $this->session = &$this->get_session(); // 引用 session
        $this->user_code = $this->session->get_sess('user_code');
        $this->role_name = $this->session->get_sess('role_code');

        $this->init();
    }

    /**
     * 初始化
     */
    public function init()
    {
        if ($this->is_weixin() && !$this->openid) {
            //五次失败后放弃
            $oauth2_count = $this->session->get_sess('oauth2_count');
            if (isset($oauth2_count) && $oauth2_count > 5) {
                $this->log_file("wx", '获到微信失败，数据为空');
                show_404();
            }
        }
    }

    /**
     * 判断是否为微信访问
     * @return bool
     */
    public function is_weixin()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return true;
    }
}