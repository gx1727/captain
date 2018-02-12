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
    }

    /**
     * 加载模型
     * @param $model_name 模型
     * @param null $alias 别名
     * @param null $modular 模块
     */
    public function model($model_name, $alias = null, $modular = null)
    {
        $class_name = $model_name; //类名 文件名
        if (strpos($model_name, '\\') === false) {
            //不带命名空空间
            $model_name = '\\' . $this->namespace . '\\' . $model_name;
        } else {
            //带命名空空间
            $class_name = substr($model_name, strrpos($model_name, '\\') + 1);
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

    public function &library($library_name, $alias = null, $modular = null)
    {

    }

    public function &help($help_name, $modular = null)
    {

    }


    /**
     * 写日志
     */
    public function log()
    {
        $args = func_get_args();
        call_user_func_array(array(&$this->log, 'log'), $args);
    }
}