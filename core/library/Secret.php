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
MIICXgIBAAKBgQDKz1i87H8T3jVnzhMmQHZzI3PKmIfmHcin6/dVqhQl/kbHhvQU
4x49vBn9thTyzlwggVScV2+qCsUwV5VqtUtaC8Oy5vRNwFULh8ux/CdpByY0TDFI
l7ZOaNOtyPy+mVT6Vd2DwatQA68MOSFcWK83YuJN9cyXNj1GrnOYznFnuwIDAQAB
AoGBAINFurZujcKabg8GwDZeO01DgUt9d1lnpd2rSqjUMb512/KCU7LBX22uN1SV
avHOyKrxi4a2wbxaxFAKINi1CFPUa85zcn78wMV5jaz1A4FMr5MlF0EFF3KCYZUj
f0EGiNnKIY/5WfmQVV15Y2lOodufxJeGUdf+ZnHeW7H4Zm/BAkEA+RmPDQDPmSzH
ZF4Q+m020ruzXM/Uzjwi1H3e7J1Z3swDHrjNAS+qjWvzEfnYhy5P4p3JM6JH/zM/
3VGgxrfpmQJBANBtiEzyPznFNUdOA927goQSQB3JXD+1v8LZ/8kCpWlwXZLBxvrv
7uFsjyFnXzXhf0fgV/x0PCStqsSYRlzKOHMCQCcgYp3SQMl4hSE4vUX4naHgJb4w
TubN/1KNKtTTqmgad2r98AV9rTZlfFqYefBRz+5yhkX7+X8WV7O1vKE6BhECQQCs
Sw8UBGCNgd7j/bKmdZ2TTX7g4JY4OCa3jPurj1trSK6hZTv2Laa7g8DhrREAelJ7
+RDiqLca3tC+SN5JUATPAkEAkGiZzCOL4mK9eQx7EI/ZiqLDYf1HmClfRgFc6380
qqdkiUOLeWQ5TVWRMxaVGY79EikWn14ve0eCHGMLsyXfLw==
-----END RSA PRIVATE KEY-----';
    }

    public function get_session()
    {
        $session = $this->session->all_userdata();
        return $session;
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
        $cipher_text = base64_encode(pack("H*", $cipher_text));
        $prikeyid = openssl_get_privatekey($this->private_key);

        if (openssl_private_decrypt(base64_decode($cipher_text), $decrypted, $prikeyid, OPENSSL_NO_PADDING)) {
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