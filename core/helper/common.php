<?php defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 全局function
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:32
 */

if (!function_exists('is_php')) {
    /**
     * 判断php版本
     *
     * @param    string
     * @return    bool    TRUE: 当前版本 >= 指定版本
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string)$version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}


/**
 * 中断
 */
if (!function_exists('error_exit')) {
    function error_exit($msg)
    {
        echo $msg;
        exit;
    }
}

/**
 * 获到配制文件
 */
if (!function_exists('sys_config')) {
    function sys_config($key)
    {
        global $configs;
        return (isset($configs[$key]) ? $configs[$key] : false);
    }
}


/**
 * 写日志
 */
if (!function_exists('log_message')) {
    function log_message()
    {
        global $captain_log;
        $args = func_get_args();

        call_user_func_array(array(&$captain_log, 'log'), $args);
    }
}

if (!function_exists('load_class')) {
    function &load_class($class_path, $class, $param = null)
    {
        global $captain_obj;
        if (isset($captain_obj[$class])) {
            return $captain_obj[$class];
        }
        require_once($class_path);

        $captain_obj[$class] = isset($param)
            ? new $class($param)
            : new $class();
        return $captain_obj[$class];
    }
}


if (!function_exists('show_404')) {
    function show_404($msg = '')
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header('Access-Control-Allow-Headers: x-requested-with,content-type');

        set_status_header(404);
        exit(4); // EXIT_UNKNOWN_FILE
    }
}

/**
 * 判断是否为命令行
 */
if (!function_exists('is_cli')) {
    function is_cli()
    {
        return (PHP_SAPI === 'cli' OR defined('STDIN'));
    }
}

/**
 * 拼接url
 */
if (!function_exists('web_url')) {
    function web_url($route_key = '', $param = array())
    {
        global $captain_router;

        $uri = '';
        if (is_array($param)) {
            $i = 0;
            foreach ($param as $key => $val) {
                $prefix = ($i == 0) ? '' : '&';
                $uri .= $prefix . $key . '=' . $val;
                $i++;
            }
        }
        $url = $captain_router->_request_scheme . '://' . $captain_router->_http_host . $captain_router->_sub_directory;

        if (sys_config('url_model') == 's') {
            if ($route_key) {
                $url .= $route_key;
                $url .= sys_config('url_suffix');
                if ($uri) {
                    $url .= '?' . $uri;
                }
            }
        } else {
            $url .= '/' . sys_config('index_page');
            if ($route_key) {
                $url .= '?r=' . $route_key;
                if ($uri) {
                    $url .= '&' . $uri;
                }
            }
        }

        return $url;
    }
}

if (!function_exists('web_root')) {
    function static_domain($domain_name = false)
    {
        if ($domain_name) {
            global $configs;
            if (isset($configs[$domain_name])) {
                return $configs[$domain_name];
            }
        }

        global $captain_router;
        return $captain_router->_request_scheme . '://' . $captain_router->_http_host . $captain_router->_sub_directory;

    }
}

/**
 * 组件功能
 */
if (!function_exists('component')) {
    function component($cmd, $args = array())
    {
        $p = strpos($cmd, ':');
        $module = substr($cmd, 0, $p); // 解析出模块名
        $cmd = substr($cmd, $p + 1);

        $p = strpos($cmd, '@');
        $controller = substr($cmd, 0, $p); // 解析出类名(带命名空间)
        $function = substr($cmd, $p + 1); // 解析出方法名

        $p = strrpos($controller, '\\');
        $class = substr($controller, $p + 1); // 解析出类名(不带命名空间)

        if (file_exists(BASEPATH . 'app' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $class . '.php')) {
            require_once BASEPATH . 'app' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $class . '.php';
            $obj = new  $controller();
            return call_user_func_array(array(&$obj, $function . 'Component'), $args);
        }
    }
}

if (!function_exists('set_status_header')) {
    /**
     * Set HTTP Status Header
     *
     * @param    int    the status code
     * @param    string
     * @return    void
     */
    function set_status_header($code = 200, $text = '')
    {
        if (is_cli()) {
            return;
        }

        if (empty($code) OR !is_numeric($code)) {
            show_error('Status codes must be numeric', 500);
        }

        if (empty($text)) {
            is_int($code) OR $code = (int)$code;
            $stati = array(
                100 => 'Continue',
                101 => 'Switching Protocols',

                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',

                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',

                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                422 => 'Unprocessable Entity',
                426 => 'Upgrade Required',
                428 => 'Precondition Required',
                429 => 'Too Many Requests',
                431 => 'Request Header Fields Too Large',

                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                511 => 'Network Authentication Required',
            );

            if (isset($stati[$code])) {
                $text = $stati[$code];
            } else {
                show_error('No status text available. Please check your status code number or supply your own message text.', 500);
            }
        }

        if (strpos(PHP_SAPI, 'cgi') === 0) {
            header('Status: ' . $code . ' ' . $text, TRUE);
            return;
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2'), TRUE))
            ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
        header($server_protocol . ' ' . $code . ' ' . $text, TRUE, $code);
    }
}