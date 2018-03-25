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

$route_config = array(
    'default' => 'cms:captain\cms\Welcome@index', // 默认处理器
);

//权限继承关系
$route_role = array(
    'guest' => array('guest'),
    'admin' => array('guest', 'admin'),
    'manager' => array('guest', 'manager'),
    'user' => array('guest', 'user')
);

/**
 * 路由指令
 * 模块名:类名(带命名空间)@方法名
 */

//游客组
$guest['/'] = 'system:captain\system\Index@index';
$guest['/hello/a'] = 'system:captain\system\Index@index';

$guest['/hello/world'] = 'system:captain\system\Index@hello';
$guest['/a*'] = 'system:captain\system\Index@hello_tag';
$guest['/hello*'] = 'system:captain\system\Index@hello_tag';
$guest['/hello/*'] = 'system:captain\system\Index@hello_art';
$guest['/cms/*'] = 'system:captain\system\Index@cms';
$guest['/z*'] = 'system:captain\system\Index@hello_tag';
$guest['/manager/login'] = 'cms:captain\cms\Manager@login';

// 游客组 - 接口类
$guest['/login'] = 'system:captain\system\Login@enter';
$guest['/role/list'] = 'system:captain\system\Auth@role_list';
$guest['/role/edit'] = 'system:captain\system\Auth@role_edit';
$guest['/menu/get_by_role'] = 'system:captain\system\Menu@get_by_role';
$guest['/menu/get_tree'] = 'system:captain\system\Menu@get_tree';

//admin管理员组
$admin['/admin/home'] = 'system:captain\system\Admin@home';

//manager管理员组
$manager['/manager/home'] = 'system:captain\system\Admin@home';

//一般用户组
$user['/user/home'] = 'system:captain\system\Admin@home';

/**
 * 无需上下文
 * 该组中的url不调session
 * $needless_context
 */
$needless_context['/api'] = 'system:captain\system\Index@api';