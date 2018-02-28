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

}