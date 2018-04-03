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

    public function index()
    {
        echo 'weixin';
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
            $weixin_oauth2 = $this->weixinMod->get($wo_id, WEIXIN_OAUTH2, 'wo_id');
            if($weixin_oauth2) {
                $weixin = $this->weixinMod->config($weixin_oauth2['weixin_code']); // 设置参数
                if($weixin_oauth2['wo_state'] == 1) { // 获到用户openid 判断用户并登录
                    $ret = $this->weixinMod->weixin_login($_GET['code']);
                    if($ret->get_code() === 3) { // 获到微信openid失败
                        $oauth2_count = $this->session->get_sess('oauth2_count');
                        if ($oauth2_count) {
                            $this->session->set_sess('oauth2_count', $oauth2_count + 1);
                        } else {
                            $this->session->set_sess('oauth2_count', 1);
                        }
                    } else if($ret->get_code() === 4) { // 微信openid还未注册
                        $weixin_oauth2 = $this->weixinMod->get_weixin_oauth2($weixin_oauth2['weixin_code'], 2); // 获到注册的配制
                        header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $weixin['weixin_appid'] . '&redirect_uri=' . urlencode($weixin_oauth2['sortwo_redirect_url'])
                            . '&response_type=code&scope=' . $weixin_oauth2['wo_scope'] . '&state=' . $weixin_oauth2['wo_id'] . '#wechat_redirect');
                        exit();
                    }
                } else if($weixin_oauth2['wo_state'] == 2) { // 获到用户详细信息 判断用户并注册新用户

                }
                header('Location: ' . $weixin_oauth2['wo_back_url']);  // 回跳
                exit();
            } else {
                $this->log_file("wx", '获取微信鉴权配制失败wo_id:' . $wo_id);
            }

            if ($state == 1) { //用户手动绑定
                $user = $this->xxweixin->oauth2_access_token($_GET['code']);
                log_message("wx", '用户手动注册 获取微信数据oauth2_access_token:' . json_encode($user));
                if ($user) {
                    $userinfo = $this->xxweixin->sns_userinfo($user);
                    log_message("wx", '获取微信数据sns_userinfo:' . json_encode($userinfo));
                    if ($user['openid'] && $userinfo['nickname']) {
                        $data = array(
                            'openid' => $user['openid'],
                            'nickname' => $userinfo['nickname'],
                            'sex' => $userinfo['sex'],
                            'city' => $userinfo['city'],
                            'province' => $userinfo['province'],
                            'country' => $userinfo['country'],
                            'headimgurl' => $userinfo['headimgurl'],
                        );

                        $ret_user = $this->userMod->register_weixin_user($user['openid'], 'ROLE00004', $userinfo['nickname'], $userinfo['headimgurl']);

                        if ($ret_user->get_code() === 0) {
                            $user_code = $ret_user->get_data();
                            $this->weixinMod->add_weixin_user($user_code, $data);
                            log_message("wx", '自动注册微信用户成功, user_code:' . $user_code);
                        } else {
                            log_message("wx", '自动注册用户失败, code:' . $ret_user->get_code());
                        }

                        if ($ret_user->get_code() === 1) {
                            log_message("wx", '本用户已绑定微信号');
                        } elseif ($ret_user->get_code() === 2) {
                            log_message("wx", '本微信号已绑定用户');
                        }
                    } else {
                        log_message("wx", '本微信号获到详细信息失败');
                    }
                    header('Location: ' . $session['back_url']);
                    exit();
                } else {
                    log_message("wx", '通过code: ' . $_GET['code'] . ' 获取用户openid失败');
                    header('Location: ' . $session['back_url']);
                    exit();
                }
            } else if ($state == 2) { //登陆
                $user = $this->xxweixin->oauth2_access_token($_GET['code']);
                log_message("wx", '登陆 获取微信数据:' . json_encode($user));
                if (isset($user['openid']) && $user['openid']) {
                    $this->openid = $user['openid'];
                    $sesion_data = array(
                        'openid' => $user['openid'],
                    );
                    $weixin = $this->weixinMod->get_weixin_by_openid($this->openid);
                    if ($weixin && $weixin['user_code']) { //用户已绑定，登陆操作
                        $login_user = $this->userMod->get_user_byusercode($weixin['user_code']);

                        $login_user['user_role'] = $this->loginMod->get_userrole($login_user['user_code']);

                        $sesion_data['user_code'] = $login_user['user_code'];
                        $sesion_data['role_code'] = $login_user['user_role'];
                        $sesion_data['logged_in'] = TRUE;

                        $this->session->set_userdata($sesion_data);
                        header('Location: ' . $session['back_url']);
                        exit();
                    } else {
                        //获取微信数据
                        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa54aa84375925ca3&redirect_uri=http%3A%2F%2Fwww.alanbeibei.com%2Fweixin%2Foauth2%2F&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
                        exit();
                    }

                } else {//获取微信数据失败，增加失败次数

                    if (isset($session['oauth2_count'])) {
                        $session['oauth2_count'] += 1;
                    } else {
                        $session['oauth2_count'] = 1;
                    }
                    $this->session->set_userdata($session);

                    header('Location: ' . $session['back_url']);
                    exit();
                }
            } else if ($state == 3) { //微信绑定
            } else if ($state == 4) { //推广注册
            }
        } else {
            header('Location: ' . $session['back_url']);
            exit();
        }
    }
}