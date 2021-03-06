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
    'admin' => array('guest', 'manager', 'admin'),
    'manager' => array('guest', 'manager'),
    'user' => array('guest', 'user')
);


/**
 * 路由指令
 * 模块名:类名(带命名空间)@方法名
 */

//游客组


// 游客组 - 接口类
$guest['/api/*'] = 'system:captain\system\Login@reject';
$guest['/api/login'] = 'system:captain\system\Login@enter';

$admin['/api/role/list'] = 'system:captain\system\Auth@role_list';
$admin['/api/role/edit'] = 'system:captain\system\Auth@role_edit';

$manager['/api/menu/tree'] = 'system:captain\system\Menu@get_tree';
$admin['/api/menu/form'] = 'system:captain\system\Menu@form_menu';
$admin['/api/menu/del'] = 'system:captain\system\Menu@del_menu';
$manager['/api/menu/get'] = 'system:captain\system\Menu@get_menu';

// 附件操作
$manager['/api/attachment/upload'] = 'system:captain\system\Attachment@upload';
$manager['/api/attachment/list'] = 'system:captain\system\Attachment@alist';
$manager['/api/attachment/network'] = 'system:captain\system\Attachment@network';

//一般用户操作
$manager['/api/user/get'] = 'system:captain\system\User@get_user';
$manager['/api/user/edit'] = 'system:captain\system\User@edit_user';
$manager['/api/user/change/pwd'] = 'system:captain\system\User@change_pwd';

// 微信相关


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
$needless_context['/'] = 'cms:captain\cms\Article@welcome';
$needless_context['/*'] = 'cms:captain\cms\Article@index';
$needless_context['/admin'] = 'ignore';
$needless_context['/api/*'] = 'ignore';
$needless_context['/tools'] = 'ignore';
$needless_context['/tools/'] = 'ignore';
$needless_context['/tools/*'] = 'ignore';
$needless_context['/preview/*'] = 'ignore';
$needless_context['/preview/template/*'] = 'ignore';

$needless_context['/tools/imagetools_proxy'] = 'system:captain\system\Tools@imagetools_proxy';

foreach ($configs['app'] as $app) {
    $routes_file = BASEPATH . 'app/' . $app . '/routes.php';
    if (file_exists($routes_file)) {
        include($routes_file);
    }
}