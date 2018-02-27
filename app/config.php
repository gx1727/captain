<?php defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 域名 （带端口）
 * 如： www.helloworld.com:21
 */
$configs['domain'] = '';

/**
 * 二级目录
 * 以 / 开始 ，结尾不带  / 如： /hello/world
 */
$configs['sub_directory'] = '';

/**
 * 后缀
 */
$configs['url_suffix'] = '.html';


/**
 * 全路径情况下的index文件名
 */
$configs['index_page'] = 'index.php';

/**
 * url 模式
 *      a: 全url模式:   http://域名:端口/二级目录/index.php?r=route&k1=v1&k2=m2
 *      s: 短url模式    http://域名:端口/route?k1=v1&k2=m2
 */
$configs['url_model'] = 's';


/**
 * 设置默认时区
 */
$configs['timezone'] = 'Asia/Shanghai';

/**
 * 默认处理器
 */
$configs['default_controller'] = 'system:captain\system\Index@def';
//$configs['default_controller'] = '';


/**
 * 数据库
 */
$configs['db'] = array(
    'mysql_host' => '127.0.0.1',
    'mysql_username' => 'root',
    'mysql_pwd' => '123456',
    'mysql_database' => 'captain',
);
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*************************************************************
 * constants
 * 常量
 */

/**
 * 数据库表名
 */
define('CAPTAIN_EXTEND', 'xx_extend'); //model扩展表

define('CAPTAIN_USER', 'xx_user'); //用户表
define("CAPTAIN_ACCOUNT", 'xx_account'); //资金帐户表 注：前缀


/**
 * 扩展说明
 */
$extends = array(
    'user' => array(
        'info' => '用户说明'
    )
);