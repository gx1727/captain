<?php
/**
 * token 、密码
 * token需要sesson支持
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1
 * Time: 10:34
 *
 * token 的使用
 * $this->library('\captain\core\Secret', 'secretLib');
 * $this->secretLib->get_token();
 * $this->secretLib->check_token($input_token);
 *
 * 如果同时需要多个token,要设置token名
 *
 * 密码 的使用
 * 使用 captain_scret.js加密的密文可以通过：
 * $this->secretLib->decoding($cipher_text);
 * secret
 * 获到到明文，这里两点注：
 * 1. 默认获取到的明文是倒序的
 * 2. 默认获取是，是带有一个名为：secret 的token处理。如果设置了 名为：secret 的token，会验证 token是否有效
 */

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

class Secret
{
    var $session;
    var $private_key;


    public function __construct()
    {
        global $captain_session;
        $this->session = &$captain_session;

        $this->private_key = '-----BEGIN RSA PRIVATE KEY-----
MIIEpgIBAAKCAQEAwL4aIEULJOYYGnGNMvORWZR978yUAysIITt1IfCSAeGf2tJV
uKhTf8qgxZGJICH1BBd6mJZxOPSSlitznlrEZJmDwHn+ktaCtCVcU6KrOO/ZJOnk
mCe3seUVTorynTHQMNfvueL4/YGqTULwt6DMs3XtVoDAXst9RR/6b4JINcYAdAvj
aNXRepxYL7P+8H8jQ0OTom2nvDGGIhX6ylasq33nYXmCxmF4uajRbQ5JfE3WKlNg
EAPpFST8FbcoZWjJWw5L8Pg8jwvRsbUHWpH6TYGSpAeADuvcrGmRqn99/pEXJFpE
eDQQed7OwvFh5UDAL9dxNPtlYeY07WJRMCWvOQIDAQABAoIBAQDAi6iR4Cd33xzp
HdLOuZ/Ue5Erw5WBbLWcbUdgdnGFy53j/geAhzeTDBt8Ax2XqhSY19OzJ2dIXJsr
xZCk+0wCq5GA8N4uVnkP+dq3+mNOZ9znEXk5v0O1RYL5iblA7AhtIurrKiFPLv+w
W1UaPFsCVKaaqdiDWol0SrPmPaJhhALLk6Wuul/lvB+uCCG4SiqWmiuLRkRCyhod
lvNbHHCZ0C8rTr3OMWtpvf/KGXbjmDmq0dhwClKYvqxtAj6LN+ENM6wRedeZ8r2r
gA+KIvwaPoCaQffel6PVlG7ix4E/qjK2AOaveqM2lgXEKa/l2F35kEZ6BrbHLt/4
MnBb7CcZAoGBAPrVDrQbrfn4ax3ed6uRZ0Gu/eNHoJwj3mCGFZKJg3lNWwoMKmsu
HgA/ZeWD7su0G2UyGVjjG3+tlRsCT5nQvYxOXb3KT9/m5dGbmXX2sD+uFBeQ5Gs5
Lrua/WiuEBU+C3xOFqX5/tDl4/BhGMDIdozmEJhTmpdoigSo6uWbtz2vAoGBAMS2
qtjQe6R87dmIIKp9Ib0+Irkxop8fTZVFGO+hql8hKlxIi+QoEVHxKin/B+K1KGsf
rK96aaIVG1nc3NNC6bzkkXzfDf3H5dftg18cuNvYw6hyEvyO61yPimhRjPZX2yyF
nzdrQQns7JBXioTp/NaF7hr2HPWtsweA4panLMOXAoGBAM3kQ0x+CAHO8wYyjWKM
WQimfoqoSeLA4pGynlSJghz47UjDEbKmyUOExrxt3n7hGTeotvuqd/EG6ASikfp9
SIm57eKTBZuRaYXzxjT5SFUNLDrn2UdWSdD++RGLB3KYWk8vvhGBsrOAcOjomqvs
ILupQWf4AEZWLiyDB0Vv2yrLAoGBAKVVztT6reE7q4H5W1NQZwoy7mZvZHqPidrb
P7ExVQwtyPfVddanIp+XrlOr6cnPb5BSCAdZQO2dBecKroPWKrS7+9hTdeQGnpVV
KOTJNEp7rz+l2XVy/jlG4BL78BmhEDMkgATJXdnky5QqL9+09vCrkswQbZjEjMPS
vR/YlNQzAoGBAKO32khEprt0zZ7V9SDeNV1FBUmL4us9W1fT9u61A2P3OC5Z75Zt
Me1o6xgEdnmRDlRNqdh+iOYxY3c5pU4dM+UsFWIPGNkgmWup/xbEvx42EACusXDo
4Po1+rtSMU1Srj96pOjQiXmvsmCQrkxs7B+B1B8iNBsYWk8N/kylFgcN
-----END RSA PRIVATE KEY-----';
    }


    /**
     * 生成一个tonken，并保存到session中
     * @param int $token_length tonken 长度
     * @param string $token_name tonken 名称
     * @param int $token_lifecycle tonken 有效周期  默认为1次
     * @param bool $strPol tonken 字符库 默认 0-9数字
     * @return null|string
     */
    public function get_token($token_length = 16, $token_name = '', $token_lifecycle = 1, $strPol = false)
    {
        $token_key = 'tonken';
        $token_lifecycle_key = 'token_lifecycle';
        if ($token_name) {
            $token_key = 'tonken_' . $token_name;
            $token_lifecycle_key = 'token_lifecycle_' . $token_name;
        }
        $token = '';
        if ($strPol) {
            $token = Rand::createRand($token_length, $strPol);
        } else {
            $token = Rand::getRandNumber($token_length);
        }
        $this->session->set_sess($token_key, $token);
        $this->session->set_sess($token_lifecycle_key, $token_lifecycle);
        return $token;
    }


    /**
     * 验证tonken ，一次验证后失效
     * @param $token_input
     * @param string $token_name
     * @return bool
     */
    public function check_token($token_input, $token_name = '')
    {
        $token_key = 'tonken';
        $token_lifecycle_key = 'token_lifecycle';
        if ($token_name) {
            $token_key = 'tonken_' . $token_name;
            $token_lifecycle_key = 'token_lifecycle_' . $token_name;
        }

        $token = $this->session->get_sess($token_key);
        $token_lifecycle = $this->session->get_sess($token_lifecycle_key);

        if ($token_lifecycle && $token_lifecycle > 1) {
            //递减tonken的有效期
            $this->session->set_sess($token_lifecycle_key, $token_lifecycle - 1);
        } else {
            $this->session->del_sess($token_key);
            $this->session->del_sess($token_lifecycle_key);
        }

        return $token && $token == $token_input;
    }


    /**
     * 解码
     * 判断token是否有效
     * 解码成功：返回发送字符的反转
     * 解码失败：返回空字符串
     * @param $cipher_text
     * @param string $token_name
     * @return string
     */
    public function decoding($cipher_text, $token_name = 'secret')
    {
        $plain_text = '';
        $prikeyid = openssl_get_privatekey($this->private_key);

        if (openssl_private_decrypt(base64_decode($cipher_text), $decrypted, $prikeyid)) {
            //解密后
            $decrypted = trim($decrypted);
            $token_local = $this->_get_token($token_name);
            if ($token_local) {
                $token_input = substr($decrypted, 0, strlen($token_local));

                if ($this->check_token(strrev($token_input))) { //因为html上传上来的明文是倒序的，故这里要再倒序后再能验证token
                    $plain_text = substr($decrypted, strlen($token_local));
                }
            } else {
                $plain_text .= $decrypted;
            }
        }
        return $plain_text;
    }

///////////////////////////////////////////////////////////////////////////////

    /**
     * @param string $token_name
     * @return bool
     */
    private
    function _get_token($token_name)
    {
        if ($token_name) {
            $token_name = 'tonken_' . $token_name;
        } else {
            $token_name = 'tonken';
        }

        return $this->session->get_sess($token_name);
    }

}