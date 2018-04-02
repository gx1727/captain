<?php defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/2
 * Time: 16:55
 */

/**
 * 0：否
 * 1：是
 */
if (!function_exists('a_status')) {
    function a_status($k, $op = "show")
    {
        $class = array(
            '0' => '删除',
            '1' => '显示',
            '2' => '未发布，不显示',
            '3' => '草稿中'
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