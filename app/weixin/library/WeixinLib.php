<?php

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 15:55
 */

namespace captain\weixin;
defined('CAPTAIN') OR exit('No direct script access allowed');

require_once(BASEPATH . "core/third_party/easyhttp/include.php");
require_once(BASEPATH . "core/third_party/weixin/wxBizMsgCrypt.php");

use \EasyHttp;

class WeixinLib
{
    var $AppID = ""; //应用ID
    var $AppSecret = "";// 应用密钥
    var $Token = ""; //令牌
    var $EncodingAESKey = "";//消息加解密密钥

//    function __construct()
//    {
//        parent::__construct(__NAMESPACE__, 'weixin');
//    }

    /**
     * 设置必要参数
     * @param $_AppID
     * @param $_AppSecret
     * @param $_Token
     * @param $_EncodingAESKey
     */
    public function config($_AppID, $_AppSecret, $_Token, $_EncodingAESKey)
    {
        $this->AppID = $_AppID;
        $this->AppSecret = $_AppSecret;
        $this->Token = $_Token;
        $this->EncodingAESKey = $_EncodingAESKey;
    }

    /**
     * 验证签名
     */
    public function valid()
    {
        $echoStr = isset($_GET["echostr"]) ? $_GET["echostr"] : '';
        $signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';
        $tmpArr = array($this->Token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        log_message("wx", "验证签名:" . $tmpStr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            echo $echoStr;
            exit;
        }
    }

    /**
     * 消息处理
     */
    public function response_msg($mod, $access_token)
    {

    }

    /**
     * oauth2 鉴权 获到 access_token
     * @param $code
     * @return bool|mixed
     */
    public function oauth2_access_token($code)
    {
        return $this->http('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->AppID
            . '&secret=' . $this->AppSecret . '&code=' . $code . '&grant_type=authorization_code',
            'GET', '');
    }

    /**
     * oauth2 鉴权 获到用户基础信息
     * @param $user
     * @return bool|mixed
     */
    public function sns_userinfo($user)
    {
        return $this->http('https://api.weixin.qq.com/sns/userinfo?access_token=' . $user['access_token'] . '&openid=' . $user['openid'] . '&lang=zh_CN',
            'GET', '');
    }

    /**
     * 获到 access_token
     * @return bool|mixed
     */
    public function get_access_token()
    {
        return $this->http('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->AppID . '&secret=' . $this->AppSecret,
            'GET', '');
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    ///
    private function http($url, $method, $body)
    {
        $http = new EasyHttp();
        $response = $http->request($url, array( //
            'method' => $method, //	GET/POST
            'timeout' => 30, //	超时的秒数
            'redirection' => 15, //	最大重定向次数
            'httpversion' => '1.0', //	1.0/1.1
            //'user-agent' => 'USER-AGENT',
            'blocking' => true, //	是否阻塞
            'headers' => array(), //	header信息
            'cookies' => array(), //	关联数组形式的cookie信息
            'body' => $body,
            'compress' => false, //	是否压缩
            'decompress' => true, //	是否自动解压缩结果
            'sslverify' => false,
            'stream' => false,
            'filename' => null //	如果stream = true，则必须设定一个临时文件名
        ));

        if ($response && isset($response['response']) && $response['response']['code'] == 200) {
            $ret_json = $response['body'];
            $ret = json_decode($ret_json, true);
            return $ret;
        } else {
            log_message('wx', '请求http失败,url:[' . $url . '] body:[' . $body . '] response:[' . json_encode($response) . ']');
        }
        return false;
    }
}