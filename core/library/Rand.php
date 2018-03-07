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
     * @param $id
     * @param $length
     * @return null|string
     */
    public static function encodeId($id, $length)
    {
        $hashtable = array(
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

        $encode = Rand::getRandNumber($length);
        $str_id = "" . $id;
        $id_length = strlen($str_id);
        if ($id_length > $length - 1) {
            $id_length = $length - 1;
        }
        $hashtable_index = 0;
        if ($id_length < $length - 1) {
            $hashtable_index = $encode[$id_length + 1];
        }

        $encode[0] = $hashtable[0][$id_length];

        for ($i = 0; $i < $id_length; $i++) {
            $encode[$i + 1] = $hashtable[(($i + $hashtable_index) % 10)][$str_id[$i]];
        }


        if (Rand::isEven($encode[0]) != Rand::isEven($encode[$length - 1])) {
            $encode = strrev($encode);
        }

        return $encode;
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