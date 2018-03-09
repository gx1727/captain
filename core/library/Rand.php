<?php
/**
 * 随机数、随机字符串
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-27
 * Time: 下午 9:15
 */

namespace captain\core;


class Rand
{
    public static function getRandChar($length)
    {
        return Rand::createRand($length, "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz");
    }

    public static function getRandNumber($length)
    {
        return Rand::createRand($length, "0123456789");
    }

    /**
     * 获到指定长度，指定字符集的随机串
     * @param $length
     * @param $str_pol
     * @return null|string
     */
    public static function createRand($length, $str_pol)
    {
        $str_rand = '';
        $max = strlen($str_pol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str_rand .= $str_pol[Rand::randNum($max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str_rand;
    }

    /**
     * 获到 0 - $max之间的一个随机数
     * @param $max
     * @return int
     */
    public static function randNum($max)
    {
        $rand_num = mt_rand(1, $max) * rand(1, $max);
        return $rand_num % $max;
    }

    /**
     * 简单加密数字ID
     * @param $code 长度限制在 1-99 位
     * @param $length
     * @return null|string
     * 如果将一个字符串藏在另一个字符串中
     * 原始字符串 $code
     * 最终字符串长度 $length
     * 信息段位数 $info_length
     *
     * 加密后的串中，第一位是开始点
     * 第二位:判断$length， 如果 $length<10, 那么第二位为原始字符串长度 第三位开始加密 ，则 $info_length = 2
     *                      如果 $length>10, 那么第二位为0,接下来两位为原始字符串长度 第5位开始加密 ，则 $info_length = 4
     * 所以：
     * 有效加密长度 $code_length，这里注意的是 $code_length <= $length - $info_length
     *                          所以， 如果要固定最终字符串长度， 当不符合上一条时，要减小有效加密长度，这样最终结果就不能解密了
     *
     * 思路是：随机一个开始点$hashtable_rand，从这个点开始使用$HASHTABLE，将sizeof($str_id) . $str_id 逐个加密到最终串中，再混淆处理
     *
     * 混淆处理，判断首尾是否同奇偶，决定是后翻转
     *
     */
    public static function encodeCode($code, $length = 0)
    {
        // hash 表
        $HASHTABLE = array(
            0 => array('0' => '6', '1' => '7', '2' => '1', '3' => '9', '4' => '0', '5' => '5', '6' => '4', '7' => '8', '8' => '3', '9' => '2'),
            1 => array('0' => '9', '1' => '5', '2' => '4', '3' => '2', '4' => '8', '5' => '0', '6' => '1', '7' => '7', '8' => '6', '9' => '3'),
            2 => array('0' => '6', '1' => '4', '2' => '2', '3' => '0', '4' => '8', '5' => '3', '6' => '5', '7' => '9', '8' => '7', '9' => '1'),
            3 => array('0' => '1', '1' => '3', '2' => '9', '3' => '0', '4' => '5', '5' => '4', '6' => '7', '7' => '2', '8' => '8', '9' => '6'),
            4 => array('0' => '1', '1' => '4', '2' => '7', '3' => '2', '4' => '8', '5' => '3', '6' => '9', '7' => '0', '8' => '6', '9' => '5'),
            5 => array('0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9'),
            6 => array('0' => '2', '1' => '8', '2' => '0', '3' => '1', '4' => '7', '5' => '3', '6' => '6', '7' => '9', '8' => '4', '9' => '5'),
            7 => array('0' => '2', '1' => '9', '2' => '7', '3' => '1', '4' => '0', '5' => '3', '6' => '8', '7' => '6', '8' => '4', '9' => '5'),
            8 => array('0' => '7', '1' => '3', '2' => '5', '3' => '2', '4' => '1', '5' => '8', '6' => '4', '7' => '9', '8' => '0', '9' => '6'),
            9 => array('0' => '9', '1' => '5', '2' => '2', '3' => '3', '4' => '8', '5' => '1', '6' => '6', '7' => '0', '8' => '7', '9' => '4'),
        );

        $code = (string)$code; // id 字符串

        // 确定 id字符串长度 $code_lenght
        $code_lenght = strlen($code);

        // 计算信息位数
        $info_length = 2;
        if ($code_lenght > 10) {
            $info_length += strlen($code_lenght);
        }

        if ($length) { //有要求最后串长度
            if ($code_lenght > $length - $info_length) {
                $code_lenght = $length - $info_length;
                if ($code_lenght <= 10) {
                    $info_length = 2;
                    $code_lenght = $length - $info_length;
                }
                $code = substr($code, -$code_lenght); // 原串有截断，取后面的字符串
            }
        } else {
            //无要求最后串长度
            $length = $code_lenght + $info_length;
        }

        $encode = Rand::getRandNumber($length); // 随机串
        $hashtable_rand = $encode[0]; // hash指针

        // 组合成要加密的$code  随机起点+原串长度+待加密数据
        $code = $hashtable_rand . (($info_length <= 2) ? '' : '0') . $code_lenght . $code;

        $index = 0;
        for ($i = 0; $i < ($code_lenght + $info_length); $i++) {
            $encode[$i] = $HASHTABLE[$index % 10][$code[$i]];
            $index = $encode[$i] + $hashtable_rand;
        }

        // 判断首尾是否同奇偶相同
        if (Rand::isEven($encode[0]) != Rand::isEven($encode[$length - 1])) {
            $encode = strrev($encode); // 有翻转
        }

        return $encode;
    }

    /**
     * 对 encodeCode 后的数据解密
     * @param $code
     * @return string
     */
    public static function decodeCode($code)
    {
        // hash 表
        $HASHTABLE = array(
            0 => array('6' => '0', '7' => '1', '1' => '2', '9' => '3', '0' => '4', '5' => '5', '4' => '6', '8' => '7', '3' => '8', '2' => '9'),
            1 => array('9' => '0', '5' => '1', '4' => '2', '2' => '3', '8' => '4', '0' => '5', '1' => '6', '7' => '7', '6' => '8', '3' => '9'),
            2 => array('6' => '0', '4' => '1', '2' => '2', '0' => '3', '8' => '4', '3' => '5', '5' => '6', '9' => '7', '7' => '8', '1' => '9'),
            3 => array('1' => '0', '3' => '1', '9' => '2', '0' => '3', '5' => '4', '4' => '5', '7' => '6', '2' => '7', '8' => '8', '6' => '9'),
            4 => array('1' => '0', '4' => '1', '7' => '2', '2' => '3', '8' => '4', '3' => '5', '9' => '6', '0' => '7', '6' => '8', '5' => '9'),
            5 => array('0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9'),
            6 => array('2' => '0', '8' => '1', '0' => '2', '1' => '3', '7' => '4', '3' => '5', '6' => '6', '9' => '7', '4' => '8', '5' => '9'),
            7 => array('2' => '0', '9' => '1', '7' => '2', '1' => '3', '0' => '4', '3' => '5', '8' => '6', '6' => '7', '4' => '8', '5' => '9'),
            8 => array('7' => '0', '3' => '1', '5' => '2', '2' => '3', '1' => '4', '8' => '5', '4' => '6', '9' => '7', '0' => '8', '6' => '9'),
            9 => array('9' => '0', '5' => '1', '2' => '2', '3' => '3', '8' => '4', '1' => '5', '6' => '6', '0' => '7', '7' => '8', '4' => '9'),
        );

        $code = (string)$code; // id 字符串

        $length = strlen($code);

        if (Rand::isEven($code[0]) != Rand::isEven($code[$length - 1])) {
            $code = strrev($code); // 有翻转
        }

        $index = 0;
        $hashtable_rand = $HASHTABLE[$index][$code[$index]];

        $code_lenght = $HASHTABLE[($hashtable_rand + $code[$index++]) % 10][$code[$index]];

        // 计算信息位数
        $info_length = 2;
        if ($code_lenght == 0) {
            $info_length = 4;
            $code_lenght = intval($HASHTABLE[($hashtable_rand + $code[$index++]) % 10][$code[$index]] . $HASHTABLE[($hashtable_rand + $code[$index++]) % 10][$code[$index]]);
        }

        $decode = '';
        for ($i = 0; $i < $code_lenght; $i++) {
            $decode .= $HASHTABLE[($hashtable_rand + $code[$index++]) % 10][$code[$index]];
        }

        return $decode;
    }

    /**
     * 是否为偶数
     * @param $number
     * @return bool
     */
    public static function isEven($number)
    {
        return (($number % 2) == 0);
    }

}