<?php

namespace captain\weixin;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 9:31
 */
include_once BASEPATH . 'app/weixin/core/WXController.php';
use \captain\weixin\WXController;

class Weixin extends WXController
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'weixin');
        $this->model('\captain\weixin\WeixinModel', 'weixinMod');
        $this->return_status[1] = '';
    }

    /**
     * @title 微信事件接管
     * @group 微信对接
     * @auth none
     * /weixin/alanbeibei
     */
    public function index()
    {
        $param = $this->input->get_uri();
        if (!isset($_GET['echostr'])) {
            $this->weixinMod->response_msg($param[0]);
        } else {
            //验证
            $this->weixinMod->valid($param[0]);
        }
    }

    /**
     * @title 鉴权接口
     * @group 微信对接
     * @auth none
     *  state:   1 用户手动绑定
     *          2 登录以后跳转到用户中心
     */
    public function oauth2()
    {
        $this->log_file("wx", '获取微信回调GET:' . json_encode($_GET));

        if (isset($_GET['code'])) {
            $wo_id = isset($_GET['state']) ? $_GET['state'] : 0;
            $weixin_oauth2 = $this->get($wo_id, WEIXIN_OAUTH2, 'wo_id');
            if ($weixin_oauth2) {
                $weixin = $this->weixinMod->config($weixin_oauth2['weixin_code']); // 设置参数
                if ($weixin_oauth2['wo_state'] == 1) { // 获到用户openid 判断用户并登录
                    $ret = $this->weixinMod->weixin_login($_GET['code']);

                    if ($ret->get_code() === 3) { // 获到微信openid失败
                        $oauth2_count = $this->session->get_sess('oauth2_count');
                        if ($oauth2_count) {
                            $this->session->set_sess('oauth2_count', $oauth2_count + 1);
                        } else {
                            $this->session->set_sess('oauth2_count', 1);
                        }
                    } else if ($ret->get_code() === 4) { // 微信openid还未注册
                        $weixin_oauth2 = $this->weixinMod->get_weixin_oauth2($weixin_oauth2['weixin_code'], 2); // 获到注册的配制
                        header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $weixin['weixin_appid'] . '&redirect_uri=' . urlencode($weixin_oauth2['sortwo_redirect_url'])
                            . '&response_type=code&scope=' . $weixin_oauth2['wo_scope'] . '&state=' . $weixin_oauth2['wo_id'] . '#wechat_redirect');
                        exit();
                    }
                } else if ($weixin_oauth2['wo_state'] == 2) { // 获到用户详细信息 判断用户并注册新用户
                    $ret = $this->weixinMod->weixin_register($_GET['code']);
                    if ($ret->get_code() === 3) { // 获到微信openid失败
                        $oauth2_count = $this->session->get_sess('oauth2_count');
                        if ($oauth2_count) {
                            $this->session->set_sess('oauth2_count', $oauth2_count + 1);
                        } else {
                            $this->session->set_sess('oauth2_count', 1);
                        }
                    }
                }
                header('Location: ' . $weixin_oauth2['wo_back_url']);  // 回跳
                exit();
            } else {
                $this->log_file("wx", '获取微信鉴权配制失败wo_id:' . $wo_id);
            }
        } else {
            echo "失败";
            exit();
        }
    }
}