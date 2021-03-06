<?php

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 9:43
 */

namespace captain\weixin;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class WeixinModel extends Model
{


    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'weixin');
        $this->table_name = WEIXIN_CONF;
        $this->key_id = 'weixin_id';

        $this->model('\captain\system\UserModel', 'userMod', 'system');
        $this->model('\captain\system\LoginModel', 'loginMod', 'system');
        $this->library('\captain\weixin\WeixinLib', 'weixinLib');

        $this->return_status[1] = "weixin配制不存在";
        $this->return_status[2] = "其它错误，自动注册用户失败";
        $this->return_status[3] = "获到微信openid失败";
        $this->return_status[4] = "微信openid还未注册";
        $this->return_status[5] = "微信openid已注册";
        $this->return_status[6] = "本微信号获到详细信息失败";

    }

    /**
     * 配制微信参数到weixinLib中
     * @param $winxin_code
     * @return bool
     */
    public function config($winxin_code)
    {
        $weixin = $this->get_weixin($winxin_code);
        if ($weixin) {
            $this->weixinLib->config($weixin['weixin_appid'], $weixin['weixin_appsecret'], $weixin['weixin_token'], $weixin['weixin_encodingaeskey']);
            return $weixin;
        } else {
            return false;
        }
    }

    /**
     * 微信登录
     */
    public function weixin_login($code)
    {
        $ret = new Ret($this->return_status);
        $oauth2_user = $this->weixinLib->oauth2_access_token($code);

        if ($oauth2_user && isset($oauth2_user['openid']) && $oauth2_user['openid']) {
            $weixin_user = $this->userMod->get_user_by_openid($oauth2_user['openid']);
            if ($weixin_user) { //用户已绑定，登陆操作
                $weixin_user['role'] = $this->loginMod->get_userrole($weixin_user['user_code']);
                $this->loginMod->login($weixin_user); // 登录的操作
                $ret->set_code(0);
            } else {
                $ret->set_code(4);
            }
        } else { // 获到用户openid失败
            $ret->set_code(3);
            $this->log_file('wx', '获到用户openid失败:' . json_encode($oauth2_user));
        }

        return $ret;
    }

    /**
     * 微信注册新用户
     */
    public function weixin_register($code)
    {
        $ret = new Ret($this->return_status);
        $oauth2_user = $this->weixinLib->oauth2_access_token($code);
        if ($oauth2_user && isset($oauth2_user['openid']) && $oauth2_user['openid']) {
            $userinfo = $this->weixinLib->sns_userinfo($oauth2_user);
            $this->log_file('wx', '获取微信数据sns_userinfo:' . json_encode($userinfo));

            if ($userinfo['openid']) {
                $weixin_data = array(
                    'openid' => $userinfo['openid'],
                    'nickname' => $userinfo['nickname'] ? $userinfo['nickname'] : '',
                    'sex' => $userinfo['sex'],
                    'city' => $userinfo['city'],
                    'province' => $userinfo['province'],
                    'country' => $userinfo['country'],
                    'headimgurl' => $userinfo['headimgurl'],
                );

                $ret_user = $this->userMod->register_weixin($weixin_data);

                if ($ret_user->get_code() === 0) {
                    $this->log_file('wx', '自动注册微信用户成功, user_code:' . $ret_user->get_data());
                    $ret->set_code(0);
                } else if ($ret_user->get_code() === 2) {
                    $this->log_file('wx', '本微信号已绑定用户');
                    $ret->set_code(5); // 微信openid已注册
                } else {
                    $this->log_file('wx', '自动注册用户失败, code:' . $ret_user->get_code());
                    $ret->set_code(2); // 其它错误，自动注册用户失败
                }
            } else {
                $this->log_file('wx', '本微信号获到详细信息失败');
                $ret->set_code(6); // 本微信号获到详细信息失败
            }
        } else { // 获到用户openid失败
            $ret->set_code(3);
            $this->log_file('wx', '获到用户openid失败:' . json_encode($oauth2_user));
        }

        return $ret;
    }

    /////////////////////////////////////////////////////////////
    ///
    ///
    /**
     * 对接验证
     */
    public function valid($weixin_code)
    {
        $this->config($weixin_code);
        $this->weixinLib->valid();
    }

    /**
     * 自动回复信息
     */
    public function response_msg($weixin_code)
    {
        $this->config($weixin_code);
        $this->weixinLib->response_msg();
    }

    /**
     * 从微信服务器获到用户信息
     * @param $weixin_code
     * @param $openid
     * @return mixed
     */
    public function sync_weixin_user_info($weixin_code, $openid)
    {
        $wx_userinfo = $this->weixinLib->get_weixin_user_info($this->get_access_token($weixin_code), $openid);
        $this->log_file('wx', '从微信服务器同步用户信息，openid:[' . $openid . '] wx:' . json_encode($wx_userinfo));
        if ($wx_userinfo) {
            $user_weixin = array(
                'subscribe' => 0
            );
            if ($wx_userinfo) {
                $user_weixin['subscribe'] = $wx_userinfo['subscribe'];
                if (isset($wx_userinfo['nickname']) && $wx_userinfo['nickname']) {
                    $user_weixin['nickname'] = $wx_userinfo['nickname'];
                }
                if (isset($wx_userinfo['headimgurl']) && $wx_userinfo['headimgurl']) {
                    $user_weixin['headimgurl'] = $wx_userinfo['headimgurl'];
                }
            }
            $this->userMod->edit($openid, $user_weixin, CAPTAIN_USERWEIXIN, 'openid');
        }
        return $wx_userinfo;
    }
    ////////////////////////////////////////////////////////////////

    /**
     * 获到获到配制信息
     * @param $weixin_code
     * @return bool
     */
    public function get_weixin($weixin_code)
    {
        if ($weixin_code) {
            return $this->get($weixin_code, WEIXIN_CONF, 'weixin_code');
        } else {
            return false;
        }
    }

    /**
     * 获到微信鉴权配制
     * @param $weixin_code
     * @param $state
     * @return mixed
     */
    public function get_weixin_oauth2($weixin_code, $state)
    {
        $sql = 'select * from ' . WEIXIN_OAUTH2 . ' where weixin_code = ? and wo_state = ?';
        return $this->query($sql, array($weixin_code, $state));
    }

    /**
     * 获取weixin 的　access_token
     * @param bool|false $weixin_code
     * @return bool
     */
    public function get_access_token($weixin_code = false)
    {
        $wx = $this->get_weixin($weixin_code);
        $access_token = false;
        if ($wx) {
            if ($wx['expire_time'] > time()) {
                $access_token = $wx['weixin_access_token'];
            } else {
                $access_token_ret = $this->weixinLib->get_access_token();
                $access_token = $access_token_ret['access_token'];
                $this->edit($wx['weixin_id'], array('weixin_access_token' => $access_token, 'expire_time' => time() + $access_token_ret['expires_in'] - 60));
            }
        }
        return $access_token;
    }
}