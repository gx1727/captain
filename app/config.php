<?php defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 域名 （带端口）
 */
$configs['domain'] = '';

/**
 * 二级目录
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
 * 默认处理器
 */
$configs['default_controller'] = 'system:index@def';


$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';