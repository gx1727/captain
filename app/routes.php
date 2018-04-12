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
$guest['/'] = 'system:captain\system\Index@index';
$guest['/*'] = 'cms:captain\cms\Article@index';
$guest['/manager/login'] = 'cms:captain\cms\Manager@login';

// 游客组 - 接口类
$guest['/api/*'] = 'system:captain\system\Login@reject';
$guest['/api/login'] = 'system:captain\system\Login@enter';

$admin['/api/role/list'] = 'system:captain\system\Auth@role_list';
$admin['/api/role/edit'] = 'system:captain\system\Auth@role_edit';

$admin['/api/menu/tree'] = 'system:captain\system\Menu@get_tree';
$admin['/api/menu/form'] = 'system:captain\system\Menu@form_menu';
$admin['/api/menu/del'] = 'system:captain\system\Menu@del_menu';

$manager['/api/menu/get'] = 'system:captain\system\Menu@get_menu';

// 文章相关
$manager['/api/cms/sort/tree'] = 'cms:captain\cms\Cms@get_sort_tree';
$admin['/api/cms/sort/form'] = 'cms:captain\cms\Cms@form_sort';
$admin['/api/cms/sort/get'] = 'cms:captain\cms\Cms@get_sort';
$admin['/api/cms/sort/del'] = 'cms:captain\cms\Cms@del_sort';
$manager['/api/cms/tag_group/list'] = 'cms:captain\cms\Cms@tag_group_list';
$admin['/api/cms/tag_group/add'] = 'cms:captain\cms\Cms@tag_group_add';
$admin['/api/cms/tag_group/edit'] = 'cms:captain\cms\Cms@tag_group_edit';
$admin['/api/cms/tag_group/del'] = 'cms:captain\cms\Cms@tag_group_del';
$manager['/api/cms/tag/list'] = 'cms:captain\cms\Cms@tag_list';
$admin['/api/cms/tag/add'] = 'cms:captain\cms\Cms@tag_add';
$admin['/api/cms/tag/edit'] = 'cms:captain\cms\Cms@tag_edit';
$admin['/api/cms/tag/del'] = 'cms:captain\cms\Cms@tag_del';
$manager['/api/cms/tag/get'] = 'cms:captain\cms\Cms@tag_get';
$manager['/api/cms/article/get'] = 'cms:captain\cms\Cms@article_get';
$manager['/api/cms/article/list'] = 'cms:captain\cms\Cms@article_list';
$manager['/api/cms/article/create'] = 'cms:captain\cms\Cms@article_create';
$manager['/api/cms/article/edit'] = 'cms:captain\cms\Cms@article_edit';
$manager['/api/cms/article/publish'] = 'cms:captain\cms\Cms@article_publish';
$manager['/api/cms/article/del'] = 'cms:captain\cms\Cms@article_del';

// 附件操作
$manager['/api/attachment/upload'] = 'system:captain\system\Attachment@upload';
$manager['/api/attachment/list'] = 'system:captain\system\Attachment@alist';

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

$needless_context['/tools/imagetools_proxy'] = 'system:captain\system\Tools@imagetools_proxy';

foreach ($configs['app'] as $app) {
    $routes_file = BASEPATH . 'app/' . $app . '/routes.php';
    if (file_exists($routes_file)) {
        include($routes_file);
    }
}