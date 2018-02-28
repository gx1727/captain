<?php
/**
 * cli方式使用captain框
 * php cli.php 路由key 参数:json格式
 * 如: php cli.php hello/a "{\"msg\": \"hello world\"}"
 *     将调用  /hello/world对应的处理器 并且$_GET["msg"]="hello world"
 *      注意：  路由key 不能以 / 开头
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/2/27
 * Time: 14:51
 */


//框架版本
define('CAPTAIN', 'CAPTAIN v3.0');

//项目的根目录
define('BASEPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

$_SERVER['HTTP_HOST'] = '';
$_SERVER['SERVER_PORT'] = '';
$_SERVER['REQUEST_METHOD'] = 'CLI';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

$_SERVER['REQUEST_URI'] = '/'; // 默认一个/
if (sizeof($argv) > 1) { // 有路由key参数
    $_SERVER['REQUEST_URI'] .= $argv[1];
}
if (isset($argv[2])) {
    $_GET = json_decode($argv[2], true);
} else {
    $_GET = array();
}

require_once BASEPATH . 'core/Captain.php';