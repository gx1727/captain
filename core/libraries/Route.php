<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:49
 */
class Route
{
    var $_request_method; // get  post ..
    var $_http_host; //域名 带端口的
    var $_server_port;
    var $_sub_directory; //二级目录


    var $_url_model;


    var $_uri;
    var $_module; //模块
    var $_controller; //控制器 带namespace
    var $_class; //类名 文件名
    var $_function; //处理的方法

    var $_routes;


    function __construct()
    {
        $this->_http_host = $_SERVER['HTTP_HOST'];
        $this->_server_port = $_SERVER['SERVER_PORT'];
        $this->_request_method = $_SERVER['REQUEST_METHOD'];

        $this->_url_model = get_config('url_model');

        $this->_routes = array(
            'needless_contex' => array(), //不需要读求session的
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
                $this->_uri = str_replace(get_config('index_page'), '', $this->_uri); //去掉 index.php
            } else if ($this->_url_model == 's') { //短url模式
                $this->_uri = str_replace(get_config('url_suffix'), '', $this->_uri); //去掉 .html
            }
        }
        $this->sub_directory();//处理二级目录
        $this->resolver(); //解析匹配
    }

    /**
     * 访问控制器方法
     */
    public function direction()
    {
        if (file_exists(BASEPATH . 'app' . DIRECTORY_SEPARATOR . $this->_module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->_class . '.php')) {
            require_once BASEPATH . 'app' . DIRECTORY_SEPARATOR . $this->_module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->_class . '.php';
            $controller = new  $this->_controller();
            call_user_func_array(array(&$controller, $this->_function), array($this));
        } else {
            show_404();
        }


    }

    /**
     * 重定向
     */
    public function redirection()
    {
        echo "dd";
    }

    /**
     * 解析匹配
     * /hello.html
     * /hello/world.html
     * /hello/1.html
     */
    public function resolver($role_name = false)
    {
        $this->init_route($role_name); //初始化路由数据

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
        if ($role_name && !$route_cmd) {
            //默认
            $route_cmd = get_config('default_controller');
            if($this->_uri[0] == '/') {
                $this->_uri = substr($this->_uri, 1); //从_uri中截掉第一个 /
            }
        }
        if ($route_cmd) {
            $p = strpos($route_cmd, ':');
            $this->_module = substr($route_cmd, 0, $p);
            $route_cmd = substr($route_cmd, $p + 1);

            $p = strpos($route_cmd, '@');
            $this->_controller = substr($route_cmd, 0, $p);
            $this->_function = substr($route_cmd, $p + 1);

            $p = strrpos($route_cmd, '\\');
            $this->_class = substr($this->_controller, $p + 1);
        }
    }

    /**
     * 初始化路由数据
     */
    protected function init_route($role_name)
    {
        $routes = array();

        //从配制文件中读取路由配制
        if ($role_name) { //需要读取session值

            global $route_role;
            foreach ($route_role as $role_key => $role) {
                if ($role_name == $role_key) {
                    foreach ($role as $route) {
                        global $$route;
                        $routes = array_merge($routes, $$route);
                    }
                }
            }
        } else {
            global $needless_context;
            $routes = $needless_context;
        }

        //分析中 两种路由模式
        $matching = array();//
        foreach ($routes as $uri_key => $route) {
            if ($uri_key[strlen($uri_key) - 1] != '*') {
                $this->_routes['strict'][$uri_key] = $route;
            } else {
                $matching[$uri_key] = $route;
            }
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
    }


    /**
     * 分析二级目录
     */
    protected function sub_directory()
    {
        $this->_sub_directory = get_config('sub_directory'); //从配制文件是读取 二级目录 配制
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