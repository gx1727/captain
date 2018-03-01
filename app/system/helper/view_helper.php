<?php defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * view中可使用的的help函数
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-17
 * Time: 上午 8:39
 */

/**
 * 继承系统view_helper
 * 模块helper中，如果文件名与系统自带helper文件名重名
 * 如需要使用系统中的function
 * 必须手动引入系统helper文件
 */
$core_path = BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR . 'view_helper.php';
if (file_exists($core_path)) {
    require_once($core_path);
}

