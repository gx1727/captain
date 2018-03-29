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
$guest['/menu/tree'] = 'system:captain\system\Menu@get_tree';
$guest['/menu/get'] = 'system:captain\system\Menu@get_menu';
$guest['/menu/form'] = 'system:captain\system\Menu@form_menu';
$guest['/menu/del'] = 'system:captain\system\Menu@del_menu';

$guest['/cms/sort/tree'] = 'cms:captain\cms\Cms@get_sort_tree';
$guest['/cms/sort/form'] = 'cms:captain\cms\Cms@form_sort';
$guest['/cms/sort/get'] = 'cms:captain\cms\Cms@get_sort';
$guest['/cms/sort/del'] = 'cms:captain\cms\Cms@del_sort';
$guest['/cms/tag_group/list'] = 'cms:captain\cms\Cms@tag_group_list';
$guest['/cms/tag_group/add'] = 'cms:captain\cms\Cms@tag_group_add';
$guest['/cms/tag_group/edit'] = 'cms:captain\cms\Cms@tag_group_edit';
$guest['/cms/tag_group/del'] = 'cms:captain\cms\Cms@tag_group_del';
$guest['/cms/tag/list'] = 'cms:captain\cms\Cms@tag_list';
$guest['/cms/tag/add'] = 'cms:captain\cms\Cms@tag_add';
$guest['/cms/tag/edit'] = 'cms:captain\cms\Cms@tag_edit';
$guest['/cms/tag/del'] = 'cms:captain\cms\Cms@tag_del';
$guest['/cms/tag/get'] = 'cms:captain\cms\Cms@tag_get';

$guest['/cms/article/create'] = 'cms:captain\cms\Cms@create_article';

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
$needless_context['/tools/imagetools_proxy'] = 'system:captain\system\Tools@imagetools_proxy';