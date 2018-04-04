<?php

namespace captain\acase;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 9:37
 */
use \captain\core\Controller;

class CaseController extends Controller
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

        $this->model('\captain\weixin\WeixinModel', 'weixinMod', 'weixin');

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
        if ($this->is_weixin() && !$this->user_code) {
            $weixin_code = $this->input->get_post('weixin', $this->weixin);
            $weixin = $this->weixinMod->get_weixin($weixin_code);
            if ($weixin) {
                $state = $this->input->get_post('state', 1);
                $weixin_oauth2 = $this->weixinMod->get_weixin_oauth2($weixin_code, $state);

                $this->log_file("wx", "微信登录中... [" . $weixin['weixin_code'] . "] back_url:" . $weixin_oauth2['wo_back_url']);

                header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $weixin['weixin_appid'] . '&redirect_uri=' . urlencode($weixin_oauth2['sortwo_redirect_url'])
                    . '&response_type=code&scope=' . $weixin_oauth2['wo_scope'] . '&state=' . $weixin_oauth2['wo_id'] . '#wechat_redirect');
                exit();
            } else {
                $this->log_file("wx", '获到微信:[' . $weixin_code . ']失败，数据为空');
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