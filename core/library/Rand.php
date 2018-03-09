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
     * 原始字符串 $str_id
     * 最终字符串长度 $length
     *
     * 加密后的串中，第一位是开始点，第二位为原始字符串长度 第三位开始加密，所以：
     * 有效加密长度 $code_length，这里注意的是 $code_length <= $length - 2
     *                          所以， 如果要固定最终字符串长度， 当不符合上一条时，要减小有效加密长度，这样最终结果就不能解密了
     *
     * 思路是：随机一个开始点$hashtable_index，从这个点开始使用$HASHTABLE，将sizeof($str_id) . $str_id 逐个加密到最终串中，再混淆处理
     * 第一步：随机一个开始点$hashtable_index，
     * 第二步：处理长度，只处理 $code_length 的个位数，如果 $code_length > 10 ，最终也只能处理到19位，所以，可加密的位数限定在 1-19位
     *          在$HASHTABLE[0]中找到 $code_length 的替换，放在$encode第一位。如：
     *          有3位，则$HASHTABLE[$hashtable_index][3] = 9, 所以$encode[1]=9;
     * 第三步：开始逐个处理
     * 第四步：混淆处理，判断首尾是否同奇偶，决定是后翻转
     * 第五步：处理长度，判断长度$code_length是否大于10，大于10，再翻转
     */
    public static function encodeId($code, $length = 0)
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
        $hashtable_index = $encode[0]; // hash指针

        echo "原始:" . $code . '<p/>';
        echo "原始长度:" . $code_lenght . '<p/>';
        echo "目标长度:" . $length . '<p/>';
        echo "随机串:" . $encode . '<p/>';
        echo "hashtable_index:" . $hashtable_index . '<p/>';

        // 第一位，使用的位移下标
        $index = 0; // 1
        $encode[$index] = $HASHTABLE[0][$hashtable_index]; // 第一位时， 使用第一组hash

        // 第二位，长度
        $hashtable_index = $encode[$index++]; //  $index 指向第 2 个位置
        $encode[$index] = $HASHTABLE[$hashtable_index][($code_lenght % 10)]; // 长度的个位数值

        for ($i = 0; $i < $code_lenght; $i++) {
            $hashtable_index = $encode[$index++]; //  $index 向后移一位
            $encode[$index] = $HASHTABLE[$hashtable_index][$code[$i]];
        }

        echo "翻转前:" . $encode . '<p/>';

        if (Rand::isEven($encode[0]) != Rand::isEven($encode[$length - 1])) {
            $encode = strrev($encode); // 有翻转
        }

        if ($code_lenght > 10) {
            echo "有翻转" . '<p/>';
            $encode = strrev($encode); // 有翻转
        }

        return $encode;
    }

    public static function decodeId($code)
    {
        echo $code;
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

        $length = sizeof($code);

        if ($length > 10) {
            $code = strrev($code); // 有翻转
        }
        $code_lenght = 0;
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