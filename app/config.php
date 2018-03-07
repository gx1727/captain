<?php defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 域名 （带端口）
 * 如： www.helloworld.com:21
 */
$configs['domain'] = '';

/**
 * 二级目录
 * 以 / 开始 ，结尾不带  / 如： /hello/world
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
 * 静态文件域名地址
 * 如： static.helloworld.com
 */
$configs['static_domain'] = '';

/**
 * url 模式
 *      a: 全url模式:   http://域名:端口/二级目录/index.php?r=route&k1=v1&k2=m2
 *      s: 短url模式    http://域名:端口/route?k1=v1&k2=m2
 */
$configs['url_model'] = 's';


/**
 * 设置默认时区
 */
$configs['timezone'] = 'Asia/Shanghai';

/**
 * 默认处理器
 */
$configs['default_controller'] = 'system:captain\system\Index@def';
//$configs['default_controller'] = '';


/**
 * 数据库
 */
$configs['db'] = array(
    'mysql_host' => '127.0.0.1',
    'mysql_username' => 'root',
    'mysql_pwd' => '123456',
    'mysql_database' => 'captain',
);

/**
 * session
 */
$configs['session'] = array(
    'sess_driver' => 'mysql',
    'sess_save_path' => 'xx_sessions',
    'sess_cookie_name' => 'PHPSESSID',
    'sess_expiration' => 600,
    'cookie_domain' => '',
    'cookie_path' => '/'
);
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*************************************************************/
// constants
// 常量
/*************************************************************/

/**
 * --------------------------------------------------------------------------
 * 数据库表名
 * --------------------------------------------------------------------------
 */
define('CAPTAIN_EXTEND', 'xx_extend'); //model扩展表

define('CAPTAIN_ROLE', 'xx_role'); //角色表

define('CAPTAIN_USER', 'xx_user'); //用户表
define('CAPTAIN_USERROLE', 'xx_user_role'); //用户角色表
define('CAPTAIN_USERWEIXIN', 'xx_user_weixin'); //用户微信表

define('CAPTAIN_CODE_INFO', 'xx_code_info'); //编码值表
define('CAPTAIN_CODE_DEFINITION', 'xx_code_definition'); //编码定义表
define('CAPTAIN_MATERIAL', 'xx_code_e'); //物资编码表 这只是前缀，后面要加上编码名


// 资金相关
define("CAPTAIN_ACCOUNT", 'xx_account'); //资金帐户表 注：前缀

/**
 * --------------------------------------------------------------------------
 *  编码相关
 * --------------------------------------------------------------------------
 */
//cd_type 编码类型
define("CD_TYPE_1", "1"); // 1:流水编码
define("CD_TYPE_2", "2"); //2:检索编码
define("CODE_START", "1"); //编码的起始值

/**
 * ------------------------------------------------------------------------
 * 默认角色
 * ------------------------------------------------------------------------
 */
define("DEFINE_ROLE", "ROLE00002"); //游客角色


/**
 * ------------------------------------------------------------------------
 * 资金操作类型
 * ------------------------------------------------------------------------
 */
define("AC_CREATE", 0); //资金 创建帐户
define("AC_DEBIT_RECHARGE", 1); //资金 入帐　用户主动充值 +
define("AC_DEBIT_RETURN_BOND", 2); //资金 入帐 +
define("AC_DEBIT_ADJUSTMENT_INCREASE", 99); //99.系统调整单 +

define("AC_CREBIT_CASH", 101); //资金 出帐　用户主动提现 -
define("AC_CREBIT_ADJUSTMENT_DECREASE", 199); //199.系统调整单-

/**
 * --------------------------------------------------------------------------
 * 扩展说明
 * --------------------------------------------------------------------------
 */
$extends = array(
    'user' => array(
        'info' => '用户说明'
    )
);

