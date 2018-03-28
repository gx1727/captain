<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-06
 * Time: 下午 10:44
 */

/**
 * 全局配制
 */
require_once BASEPATH . 'app/config.php';
require_once BASEPATH . 'app/routes.php';


/**
 * 全局方法
 */
include_once BASEPATH . 'core/helper/common.php';

/**
 * 设置时区
 */
date_default_timezone_set(sys_config('timezone'));

/**
 * 工具类
 */
include_once BASEPATH . 'core/library/Ret.php';
include_once BASEPATH . 'core/library/Rand.php';
include_once BASEPATH . 'core/library/Base.php';

/**
 * 全局类
 */

include_once BASEPATH . 'core/Model.php';
include_once BASEPATH . 'core/Route.php';
include_once BASEPATH . 'core/Input.php';
include_once BASEPATH . 'core/Log.php';
include_once BASEPATH . 'core/Session.php';
include_once BASEPATH . 'core/Controller.php';


/**
 * 日志
 */
$captain_log = new Log(BASEPATH);

/**
 * 数据库连接
 * 当使用时，判断数据库是否连接
 * 一次接求只连接一次
 */
$captain_db = false;

/**
 * 全局对象池
 */
$captain_obj = array();
/**
 * 当前用户的角色
 */
$role = 'guest';
//通过session获到当前用户的角色
$captain_session = false;

/**
 * 初始化路由
 */
$captain_router = new Route();
if (!$captain_router->success()) { // 在needless_context中没有匹配命中
    $captain_session = new Session(sys_config('session'));
    $captain_router->resolver($role); //解析匹配
}

$captain_input = new Input($captain_router->_uri);
$captain_input->get_contents(); // 将数据整合到post 中

$captain_router->direction(); //指向最终处理函数

if ($captain_db) {
    $captain_db->disconnect();
}
