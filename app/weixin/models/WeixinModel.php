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
            $weixin_user = $this->weixinMod->get_user_by_openid($oauth2_user['openid']);
            if ($weixin_user && $weixin_user['user_code']) { //用户已绑定，登陆操作
                $login_user = $this->userMod->get_user($weixin_user['user_code']);
                $login_user['role'] = $this->loginMod->get_userrole($login_user['user_code']);
                $this->loginMod->login($login_user); // 登录的操作
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
            $userinfo = $this->xxweixin->sns_userinfo($oauth2_user);
            $this->log_file('wx', '获取微信数据sns_userinfo:' . json_encode($userinfo));

            if ($userinfo['openid']) {
                $weixin_data = array(
                    'openid' => $oauth2_user['openid'],
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
    public function valid()
    {
        $this->weixinLib->valid();
    }

    /**
     * 自动回复信息
     */
    public function response_msg()
    {
        $this->weixinLib->response_msg();
    }

    ////////////////////////////////////////////////////////////////

    /**
     * 能过openid获到用户信息
     * @param $openid
     * @return bool|mixed
     */
    public function get_user_by_openid($openid)
    {
        $weixin = $this->get($openid, CAPTAIN_USERWEIXIN, 'openid');
        if ($weixin) {
            $user = $this->get($weixin['user_code'], XS_USER, 'user_code');
            if (!$user) {
                //指定微信用户不存在
                $this->log_file('wx', "微信用户不存在,需要删除微信记录" . json_encode($weixin));
                $this->del($weixin['uw_id'], CAPTAIN_USERWEIXIN, 'uw_id');
                $weixin = false;
            }
        }

        return $weixin;
    }

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
                $access_token_ret = $this->xxweixin->get_access_token();
                $access_token = $access_token_ret['access_token'];
                $this->edit($wx['weixin_id'], array('weixin_access_token' => $access_token, 'expire_time' => time() + $access_token_ret['expires_in'] - 60));
            }
        }
        return $access_token;
    }
}