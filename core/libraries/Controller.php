<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:56
 */
class Controller
{
    var $log;
    var $router;
    var $input;

    var $modular; //模块
    var $namespace; //当前命名空间

    var $view_data; //传给view的数据
    var $return_status; //返回的信息

    function __construct($modular, $namespace)
    {
        global $captain_log,
               $captain_router,
               $captain_input;
        $this->log = &$captain_log;
        $this->router = &$captain_router;
        $this->input =  &$captain_input;

        $this->modular = $modular;
        $this->namespace = $namespace;

        $this->view_data = array();

        $this->return_status = array();
        $this->return_status[0] = "成功";
        $this->return_status[999] = "无效的返回状态";
    }

    /**
     * 加载模型
     * @param $model_name 模型
     * @param null $alias 别名
     * @param null $modular 模块
     */
    protected function model($model_name, $alias = null, $modular = null)
    {
        $class_name = $model_name; //类名 文件名
        if (strpos($model_name, '\\') === false) {  //不带命名空间 自动补齐默认的 命名空间
            $model_name = '\\' . $this->namespace . '\\' . $model_name;
        } else { //带命名空间
            $class_name = substr($model_name, strrpos($model_name, '\\') + 1); //获到类名
        }

        if (!$alias) {
            $alias = $class_name;
        }
        if (!$modular) {
            $modular = $this->modular;
        }
        $file_path = BASEPATH . 'app' . DIRECTORY_SEPARATOR . $modular . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $class_name . '.php';

        if (file_exists($file_path)) {
            $this->$alias = load_class($file_path, $model_name);
        }
    }

    /**
     * 加载类库
     * @param $library_name 模型
     * @param null $alias 别名
     * @param null $modular 模块
     */
    protected function library($library_name, $alias = null, $modular = null)
    {
        $class_name = $library_name; //类名 文件名
        if (strpos($library_name, '\\') === false) {
            //不带命名空空间
            $library_name = '\\' . $this->namespace . '\\' . $library_name;
        } else {
            //带命名空间
            $class_name = substr($library_name, strrpos($library_name, '\\') + 1);
        }

        if (!$alias) {
            $alias = $class_name;
        }
        if (!$modular) {
            $modular = $this->modular;
        }
        $file_path = BASEPATH . 'app' . DIRECTORY_SEPARATOR . $modular . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . $class_name . '.php';

        if (file_exists($file_path)) {
            $this->$alias = load_class($file_path, $library_name);
        } else {
            //模块不存在类库文件
            $file_path = BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . $class_name . '.php';
            if (file_exists($file_path)) {
                $this->$alias = load_class($file_path, $library_name);
            }
        }
    }

    /**
     * 加载help
     * @param $help_name 模型
     * @param null $modular 模块
     */
    protected function help($help_name, $modular = null)
    {
        if (!$modular) {
            $modular = $this->modular;
        }

        $file_path = BASEPATH . 'app' . DIRECTORY_SEPARATOR . $modular . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . $help_name . '.php';

        if (file_exists($file_path)) {
            require_once($file_path);
        } else {
            //模块不存在类库文件
            $file_path = BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . $help_name . '.php';
            if (file_exists($file_path)) {
                require_once($file_path);
            }
        }
    }

    /**
     * 向页面传递参数
     * @param $k
     * @param $v
     */
    protected function assign($k, $v)
    {
        $this->view_data[$k] = $v;
    }

    /**
     * 返回json串
     * @param $data
     * @param bool $oflag 如果为真,则$data为数据,需要encode
     */
    protected function json($data, $oflag = true)
    {
        if ($oflag) {
            $data = json_encode($data);
        }
        header('Content-Type: application/json');
        echo $data;
        exit;
    }

    /**
     * 调用模板，显示到浏览器
     * @param $view
     */
    protected function view($view)
    {
        extract($this->view_data);
        include(BASEPATH . 'app/' . $this->modular . '/views/' . $view . '.php');
        exit;
    }

    /**
     * 通过返回值，获取描述性文字
     * 排除非法key导致php报错
     * @param $key
     * @return mixed
     */
    public function status_msg($key)
    {
        if (is_null($key) || is_array($key) || is_object($key)) {
            return $this->return_status[999];
        }
        if (isset($this->return_status[$key])) {
            return $this->return_status[$key];
        } else {
            return $this->return_status[999];
        }
    }

    /**
     * 获取返回值
     * 使用 Controller 返回时 ： get_result(true, code值)
     * @param $ret
     * @param int $code
     * @param array $param
     * @return array
     */
    public function get_result($ret, $code = 0, $param = array())
    {
        $ret_array = array('code' => $code, 'msg' => $this->status_msg($code));
        foreach ($param as $k => $v) {
            $ret_array['msg'] = str_replace($k, $v, $ret_array['msg']);
        }
        if ($ret instanceof Ret) {
            $ret_array = $ret->get_result();
        } else if (is_object($ret)) {
            $ret_array = get_object_vars($ret);
            $ret_array['code'] = 0;
            $ret_array['msg'] = '';
        } else if (is_array($ret)) {
            $ret_array = array_merge($ret, $ret_array);
        } else if (is_numeric($ret)) {
            $ret_array['id'] = $ret;
        } else if (is_bool($ret) && $code == 0) {//针对   get_result(false)  的处理，直接返回1 失败
            if (!$ret) {
                $ret_array['code'] = 1;
                $ret_array['msg'] = '失败';
            }
        }

        return $ret_array;
    }

    /**
     * 写日志
     * 只有一个参数时，常规日志
     * 多个参数时，常规format
     */
    protected function log()
    {
        $args = func_get_args();
        if (sizeof($args) > 1) { // 当多个参数时，自动插入常规日志前缀 ''
            array_unshift($args, '');
        }
        call_user_func_array(array(&$this->log, 'log'), $args);
    }

    /**
     * 指定日志文件名写日志
     * 第一个参数为日志文件前缀
     */
    protected function log_file()
    {
        $args = func_get_args();
        call_user_func_array(array(&$this->log, 'log'), $args);
    }
}