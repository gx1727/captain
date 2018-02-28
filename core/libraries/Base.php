<?php
/**
 * 基础类
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/2/28
 * Time: 10:59
 */

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

class Base
{
    var $log; //日志
    var $db;//数据库

    var $modular; //模块
    var $namespace; //当前命名空间

    function __construct($namespace, $modular = '')
    {
        global $captain_db,
               $captain_log;
        $this->db = &$captain_db;
        $this->log = &$captain_log;

        $this->modular = $modular;
        $this->namespace = $namespace;
    }

    ///////////////////////////////////////////////////////////////////////////////
    /// 处理日志
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

    ///////////////////////////////////////////////////////////////////////////////
    /// 处理数据库
    /**
     * 数据库连接
     */
    public function database()
    {
        if (!$this->db) {
            //建立连接
            require_once(BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'third_party' . DIRECTORY_SEPARATOR . 'MysqliDb.php');
            $db_config = sys_config('db');
            $this->db = new \MysqliDb ($db_config['mysql_host'], $db_config['mysql_username'], $db_config['mysql_pwd'], $db_config['mysql_database']);
        }
    }

    /**
     * 普过ID获取值，要求所获取的表都有一个ID字段
     * @param $key
     * @param string $table
     * @param string $key_field
     * @return bool
     */
    public function get($key, $table = '', $key_field = '')
    {
        $ret = false;
        if (!$table) {
            $table = $this->table_name;
        }
        if (!$key_field) {
            $key_field = $this->key_id;
        }

        if ($key && $table && $key_field) {
            $this->database(); //初始化数据库连接
            $sql = "select * from  $table where $key_field = ?";
            $ret = $this->db->rawQueryOne($sql, array($key));
        }
        return $ret;
    }

    /**
     * 获取所有
     * @param $key
     * @param string $table
     * @param string $key_field
     * @return bool
     */
    public function get_all($key, $table = '', $key_field = '')
    {
        $ret = false;
        if (!$table) {
            $table = $this->table_name;
        }
        if (!$key_field) {
            $key_field = $this->key_id;
        }

        if ($key && $table && $key) {
            $this->database(); //初始化数据库连接
            if ($key_field == "where") {
                $sql = "select * from  $table where $key";
                $ret = $this->db->rawQuery($sql);
            } else {
                $sql = "select * from  $table where $key_field = ?";
                $ret = $this->db->rawQuery($sql, array($key));
            }
        }
        return $ret;
    }

    /**
     * 插入新数据
     * @param $data
     * @param string $table
     * @return mixed
     */
    public function add($data, $table = '')
    {
        if (!$table) {
            $table = $this->table_name;
        }
        return $this->db->insert($table, $data);
    }

    /**
     * 修改数据库
     * @param $key
     * @param $data
     * @param string $table
     * @param string $key_field
     * @return mixed
     */
    public function edit($key, $data, $table = '', $key_field = '')
    {
        $this->database(); //初始化数据库连接

        if (!$table) {
            $table = $this->table_name;
        }
        if (!$key_field) {
            $key_field = $this->key_id;
        }

        if ($key_field == "where") {
            $this->db->where($key);
        } else {
            $this->db->where($key_field, $key);
        }
        return $this->db->update($table, $data);
    }

    /**
     * 删除数据
     * @param $key
     * @param string $table
     * @param string $key_field
     * @return mixed
     */
    public function del($key, $table = '', $key_field = '')
    {
        $this->database(); //初始化数据库连接
        if (!$table) {
            $table = $this->table_name;
        }
        if (!$key_field) {
            $key_field = $this->key_id;
        }

        $this->db->where($key_field, $key);
        return $this->db->delete($table);
    }

    ///////////////////////////////////////////////////////////////////////////////
    /// 处理加载

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
}