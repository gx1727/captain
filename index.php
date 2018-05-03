<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-06
 * Time: 下午 10:11
 */
//declare(strict_types=1);
$cors_flag = true;
function cors()
{
    global $cors_flag;
    if ($cors_flag) {
        header("Access-Control-Allow-Credentials:true");
        header("Access-Control-Allow-Origin: http://localhost:8080");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header('Access-Control-Allow-Headers: x-requested-with,content-type');
        $cors_flag = false;
    }
}

/**
 * 如果为 OPTIONS 协议，什么也不做
 */
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
    cors();
    // 什么也不做
    return;
}

//框架版本
define('CAPTAIN', 'CAPTAIN v3.0');

//项目的根目录
define('BASEPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once BASEPATH . 'core/Captain.php';