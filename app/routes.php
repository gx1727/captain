<?php defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 路由配置
 * 如：
 *      $guest['/index'] = 'system:class@function';
 *          /index： uri中有路径
 *          system   ： 模块
 *          class ： 类名
 *          function：方法名
 *  此时为严格uri, 当前严格uri时，uri中除了匹配到的/cms后，不应有其它字符中，参数一律在 ? 号之后 ,如cms/1.html 当匹配失败
 *
 *
 * 详细配置，如：
 *      $guest['/cms/*'] = 'system:index@cms';
 *  此时为非严格uri  可以匹配如 : /cms/1.html
 *
 *      $guest['/list*'] = 'system:index@cms';
 * 此时为非严格uri  可以匹配如 : /listhello.html   /list_page/1.html
 */

/**
 * 普通访客，基础权限
 */

$guest = array();
$admin = array();
$manager = array();
$user = array();


//权限继承关系
$route_role = array(
    'guest' => array('guest'),
    'admin' => array('guest', 'admin'),
    'manager' => array('guest', 'manager'),
    'user' => array('guest', 'user')
);

$guest['/'] = 'system:captain\system\Index@index';

$guest['/hello/world'] = 'system:captain\system\Index@hello';
$guest['/a*'] = 'system:captain\system\Index@hello_tag';
$guest['/hello*'] = 'system:captain\system\Index@hello_tag';
$guest['/hello/*'] = 'system:captain\system\Index@hello_art';
$guest['/cms/*'] = 'system:captain\system\Index@cms';
$guest['/z*'] = 'system:captain\system\Index@hello_tag';

$admin['/admin/home'] = 'system:admin@home';