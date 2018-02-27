<?php defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * view中可使用的的help函数
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-17
 * Time: 上午 8:39
 */

/**
 * 0：否
 * 1：是
 */
if (!function_exists('yes_no')) {
    function yes_no($k, $op = "show")
    {
        $class = array(
            '0' => '否',
            '1' => '是',
        );
        if ($op == "option") {
            foreach ($class as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
            }
        } else if ($op == "class") {
            return $class;
        } else {
            return isset($class[$k]) ? $class[$k] : $k;
        }
    }
}

/**
 *  '0' => '保密',
 * '1' => '男',
 * '2' => '女',
 */
if (!function_exists('man_female ')) {
    function man_female($k, $op = "show")
    {
        $class = array(
            '0' => '保密',
            '1' => '男',
            '2' => '女',
        );
        if ($op == "option") {
            foreach ($class as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
            }
        } else if ($op == "class") {
            return $class;
        } else {
            return isset($class[$k]) ? $class[$k] : $k;
        }
    }
}