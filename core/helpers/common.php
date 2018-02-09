<?php defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 全局function
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:32
 */

if (!function_exists('is_php')) {
    /**
     * 判断php版本
     *
     * @param    string
     * @return    bool    TRUE: 当前版本 >= 指定版本
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string)$version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}


/**
 * 中断
 */
if (!function_exists('error_exit')) {
    function error_exit($msg)
    {
        echo $msg;
        exit;
    }
}

/**
 * 获到配制文件
 */
if (!function_exists('get_config')) {
    function get_config($key)
    {
        global $configs;
        return (isset($configs[$key]) ? $configs[$key] : false);
    }
}