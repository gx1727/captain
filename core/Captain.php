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
include_once BASEPATH . 'core/helpers/common.php';

/**
 * 全局类
 */
include_once BASEPATH . 'core/libraries/Ret.php';
include_once BASEPATH . 'core/libraries/Route.php';
include_once BASEPATH . 'core/libraries/Input.php';
include_once BASEPATH . 'core/libraries/Controller.php';

//require_once BASEPATH . 'app/system/controllers/Index.php';
/**
 * 测试
 * 模块方法
 */

/**
 * 当前用户的角色
 */
$role = 'guest';
//通过session获到当前用户的角色

/**
 * 初始化路由
 */
$captain_router = new Route();
if (!$captain_router->_class) { // 在needless_context中没有匹配命中
    $captain_router->resolver($role); //解析匹配
}

$captain_input = new Input($captain_router->_uri);
$captain_router->direction(); //指向最终处理函数
//$controller = new (ucfirst($router->_controller))();
//print_r($controller);