<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 路由
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:49
 */
class Route
{
    var $_request_method; // get  post ..
    var $_request_scheme; // http https
    var $_http_host; // 域名 带端口的
    var $_server_port;
    var $_sub_directory; // 二级目录

    var $_url_model; // url 模式

    var $_uri;
    var $_module; // 模块
    var $_controller; // 控制器 带namespace
    var $_class; // 类名 文件名
    var $_function; // 处理的方法

    var $_routes; // 路由配制数据

    var $route_cmd_history = array(); // 路由解析历史，防止在重定向时，出现死循环
    var $max_route_redirection_count = 3; //同一个路由项最多出现次数，大于该次数，视为死循环

    var $default_controller; //默认处理器

    function __construct()
    {
        $this->_request_scheme = $_SERVER['REQUEST_SCHEME'];
        $this->_http_host = $_SERVER['HTTP_HOST'];
        $this->_server_port = $_SERVER['SERVER_PORT'];
        $this->_request_method = $_SERVER['REQUEST_METHOD'];

        /**  默认处理器 */
        global $route_config;
        $this->default_controller = $route_config['default'];

        /**  url 模式  */
        $this->_url_model = sys_config('url_model');

        $this->_routes = array(
            'needless_context' => array(), //不需要读求session的
            'strict' => array(), //严格模式
            'matching' => array() //匹配模式
        );

        //处理uri, 截断?后的所有数据
        $uri_pos_index = strpos($_SERVER['REQUEST_URI'], '?');
        if ($uri_pos_index && $uri_pos_index > 0) {
            $this->_uri = substr($_SERVER['REQUEST_URI'], 0, $uri_pos_index);
        } else {
            $this->_uri = $_SERVER['REQUEST_URI'];
        }

        if ($this->_uri != '/') {
            if ($this->_url_model == 'a') { //全url模式 必须有index.html
                $this->sub_directory();//处理二级目录
                $this->_uri = str_replace(sys_config('index_page'), '', $this->_uri); //去掉 .html
                if (isset($_GET['r'])) {
                    $this->_uri = $_GET['r'];
                }
            } else if ($this->_url_model == 's') { //短url模式
                $this->_uri = str_replace(sys_config('index_page'), '', $this->_uri); //去掉 .html
                $this->_uri = str_replace(sys_config('url_suffix'), '', $this->_uri); //去掉 .html
                $this->sub_directory();//处理二级目录
            }
        }
        $this->resolver(); //解析匹配 参数为空，只解析无需上下文的路由
    }

    /**
     * 访问控制器方法
     */
    public function direction()
    {
        if (file_exists(BASEPATH . 'app' . DIRECTORY_SEPARATOR . $this->_module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->_class . '.php')) {
            require_once BASEPATH . 'app' . DIRECTORY_SEPARATOR . $this->_module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->_class . '.php';
            $controller = new  $this->_controller();
            call_user_func_array(array(&$controller, $this->_function), array());
        } else {
            show_404();
        }


    }

    /**
     * 重定向
     */
    public function forward($route_cmd)
    {
        $this->analysis($route_cmd);
        $this->direction();
    }

    /**
     * 跳转
     * @param $url
     */
    public function redirect($url)
    {
        header("Location: " . $url);
    }

    /**
     * 解析匹配
     * /hello.html
     * /hello/world.html
     * /hello/1.html
     */
    public function resolver($role_name = false)
    {
        //初始化路由数据
        if ($this->init_route($role_name)) {
            $route_cmd = false;
            //匹配 严格模式
            foreach ($this->_routes['strict'] as $key => $route) {
                if ($key == $this->_uri) {
                    //命中
                    $route_cmd = $route;
                    $this->_uri = substr($this->_uri, strlen($key)); //从_uri中截掉$key
                    break;
                }
            }
            if (!$route_cmd) {
                // 匹配模式
                foreach ($this->_routes['matching'] as $key => $route) {
                    if (strpos($this->_uri, $key) === 0) {
                        $route_cmd = $route;
                        $this->_uri = substr($this->_uri, strlen($key)); //从_uri中截掉$key
                        break;
                    }
                }
            }
            if ($role_name && !$route_cmd) { // 当有角色名 但 没有匹配到路由里， 使用默认路由处理
                $route_cmd = $this->default_controller;// 获取默认值
                if ($this->_uri[0] == '/') {
                    $this->_uri = substr($this->_uri, 1); //从_uri中截掉第一个 /
                }
            }

            $this->analysis($route_cmd);
        }
    }

    /**
     * 判断 是否解析成功
     * @return bool
     */
    public function success()
    {
        return $this->_module && $this->_controller && $this->_function;
    }

    /**
     * 解析路由指令
     * 模块名:类名(带命名空间)@方法名
     * @param $route_cmd
     */
    protected function analysis($route_cmd)
    {
        if ($route_cmd) {
            $count = 0;
            foreach ($this->route_cmd_history as $route_cmd_old) {
                if ($route_cmd == $route_cmd_old) {
                    $count++;;
                }
            }
            if ($count >= $this->max_route_redirection_count) {
                error_exit('死循环');
            }
            $this->route_cmd_history[] = $route_cmd;

            $p = strpos($route_cmd, ':');
            $this->_module = substr($route_cmd, 0, $p); // 解析出模块名
            $route_cmd = substr($route_cmd, $p + 1);

            $p = strpos($route_cmd, '@');
            $this->_controller = substr($route_cmd, 0, $p); // 解析出类名(带命名空间)
            $this->_function = substr($route_cmd, $p + 1); // 解析出方法名

            $p = strrpos($route_cmd, '\\');
            $this->_class = substr($this->_controller, $p + 1); // 解析出类名(不带命名空间)
        }
    }

    /**
     * 初始化路由数据
     * 从routes.php中获取路由配制数据，根据路中，key值是否带有*号，分为两组
     *      一组不带*号，严格路由模式 存入 $this->_routes['strict']
     *      一组带*号，  匹配路由模式 存入 $this->_routes['matching']
     *
     * @param $role_name 角色名
     *
     * @return boolean
     */
    protected function init_route($role_name)
    {
        $routes = array();

        // 从配制文件中读取路由配制
        if ($role_name) { // 需要读取session值

            global $route_role; // 在 routes.php中配制
            foreach ($route_role as $role_key => $role) {
                if ($role_name == $role_key) {
                    foreach ($role as $route) {
                        global $$route; // 在 routes.php中配制
                        $routes = array_merge($routes, $$route);
                    }
                }
            }
        } else { // 无需读取session值
            global $needless_context; // 在 routes.php中配制
            $routes = $needless_context;
        }

        //分析中 两种路由模式
        $matching = array();//
        if (is_array($routes)) {
            foreach ($routes as $uri_key => $route) {
                if ($uri_key[strlen($uri_key) - 1] != '*') { //只匹配末尾的*号
                    $this->_routes['strict'][$uri_key] = $route;
                } else {
                    $matching[$uri_key] = $route;
                }
            }
        } else {
            return false; //没有对应的路由配制，返回false
        }
        if ($matching) {
            //对匹配模式接从长到短排序
            $key_len = array();
            foreach ($matching as $key => $tmp) {
                $key_len[$key] = strlen($key);
            }
            arsort($key_len); //排序
            foreach ($key_len as $key => $tmp) {
                //删除最后面的 * 号
                $this->_routes['matching'][substr($key, 0, strlen($key) - 1)] = $matching[$key];
            }
        }

        return true;
    }

    /**
     * 分析、获取二级目录
     */
    protected function sub_directory()
    {
        $this->_sub_directory = sys_config('sub_directory'); //从配制文件是读取 二级目录 配制
        if (!$this->_sub_directory) { //没有相关配制，直接计算获到
            $sub_directory = str_replace('\\', '/', BASEPATH);
            $sub_directory = rtrim($sub_directory, '/');
            while ($sub_directory) {
                if (strpos($this->_uri, $sub_directory) === 0) {
                    break;
                }
                $sub_directory = substr($sub_directory, 1);
            }
            $this->_sub_directory = $sub_directory;//获到二级目录
        }

        //处理uri,截到前总能的二级目录部分
        $this->_uri = substr($this->_uri, strlen($this->_sub_directory));
    }

}