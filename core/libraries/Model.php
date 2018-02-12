<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 9:54
 */
class Model
{
    var $db;//数据库
    var $modular;

    /**
     * 模块的返回值
     * @var
     */
    public $return_status;

    /** 主键字段名 */
    public $key_id;

    /** 默认的表名 */
    public $table_name;

    /** 默认扩展表*/
    public $table_name_extend = CAPTAIN_EXTEND;


    function __construct()
    {
        global $captain_db;
        $this->db = &$captain_db;

        /**
         * 对模块的返回值进行扩展
         */
        $this->return_status = array();
        $this->return_status[0] = "成功";
        $this->return_status[999] = "无效的返回状态";
    }

    /**
     * 加载模型
     */
    public function &model($model_name, $modular = null)
    {

    }

    public function &library($library_name, $modular = null)
    {

    }

    public function &help($help_name, $modular = null)
    {

    }

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

        if ($key && $table && $key) {
            $this->database(); //初始化数据库连接
            $sql = "select * from  $table where $key = ?";
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


    /**
     * 获取列表
     * @param $return_status
     * @param $sql
     * @param $param_array
     * @param $start
     * @param $page_size
     * @param $order_column
     * @param $order_dir
     * @param string $field
     * @return Ret
     */
    public function get_page_list(&$return_status, $sql, $param_array, $start, $page_size, $order_column, $order_dir, $field = '*')
    {
        $ret = new Ret($return_status);
        $this->database(); //初始化数据库连接
        if (!$param_array) {
            $param_array = false;
        }
        $count_sql = "select count(1) as count " . $sql;

        $row = $this->db->rawQueryOne($count_sql, $param_array);
        if ($row['count'] > 0) {
            $ret->set_code(0); //设置返回结果为成功
            $ret->set_data($row['count'], 2); //结果数放在第二个位置上

            $data_sql = 'select ' . $field . ' ' . $sql;
            if ($order_column && $order_dir) {
                $data_sql .= ' order by ' . $order_column . ' ' . $order_dir;
            }
            if ($page_size >= 0) {
                $start = $start >= 0 ? $start : 0; //不能小于0
                $data_sql .= " limit $start, " . $page_size;
            }

            $data_query = $this->db->rawQuery($data_sql, $param_array);
            $ret->set_data($data_query);
        } else {
            //没有数据
            $ret->set_data(false);
            $ret->set_data(0, 2);
            $ret->set_code(1);
        }
        return $ret;
    }

    /************************************************************************
     * 扩展数据
     */
    /**
     * 插入扩展数据
     * @param $foreign_key 关链ID
     * @param $class 扩展所属
     * @param $sort  扩展分类
     * @param bool $title 标题
     * @param bool $data 数据
     * @return bool
     */
    public function add_extend($foreign_key, $class, $sort, $title = false, $data = false)
    {
        $data = array(
            'foreign_id' => $foreign_key,
            'e_class' => $class,
            'e_sort' => $sort,
            'e_title' => $title,
            'e_data' => $data,
            'e_atime' => time(),
            'e_etime' => time(),
        );
        return $this->add($data, $this->table_name_extend);
    }

    /**
     * 修改数据
     * @param $id
     * @param $foreign_key
     * @param $class
     * @param bool $sort
     * @param bool $title
     * @param bool $data
     * @return mixed
     */
    public function edit_extend($id, $foreign_key, $class, $sort = false, $title = false, $data = false)
    {
        $extend_data = array(
            'foreign_id' => $foreign_key,
            'e_class' => $class,
            'e_etime' => time(),
        );
        if ($sort !== false) {
            $extend_data['e_sort'] = $sort;
        }
        if ($title !== false) {
            $extend_data['e_title'] = $title;
        }

        if ($data !== false) {
            $extend_data['e_data'] = $data;
        }
        return $this->edit($id, $extend_data, $this->table_name_extend, 'e_id');
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function del_extend($id)
    {
        return $this->edit($id, array('e_status' => 1), $this->table_name_extend, 'e_id');
    }

    /**
     * 根据参数据获到数据
     * @param $foreign_key
     * @param bool $class
     * @param bool $sort
     * @param bool $all 是否获取所有值
     * @return bool
     */
    public function get_extend($foreign_key = false, $class = false, $sort = false, $all = false)
    {
        $sql = 'select * from ' . $this->table_name_extend . ' where e_status = 0 ';
        $param_array = array();
        if ($foreign_key !== false) {
            $sql .= ' and foreign_id = ?';
            $param_array[] = $foreign_key;
        }
        if ($class !== false) {
            $sql .= ' and e_class = ?';
            $param_array[] = $class;
        }
        if ($sort !== false) {
            $sql .= ' and e_sort = ?';
            $param_array[] = $sort;
        }

        $ret = false;
        if ($all) {
            //所有值
            $ret = $this->db->rawQuery($sql, $param_array);
        } else {
            //
            $ret = $this->db->rawQueryOne($sql, $param_array);
        }
        return $ret;
    }

    /**
     * 获到带分页的列表
     * @param $page_page
     * @param $page_pagesize
     * @param $page_order
     * @param $page_type
     * @param $keyword
     * @param $foreign_key
     * @param bool $class
     * @param bool $sort
     * @return Ret
     */
    public function get_list($page_page, $page_pagesize, $page_order, $page_type, $keyword, $foreign_key = false, $class = false, $sort = false)
    {
        $param_array = array();
        $sql = 'from ' . $this->table_name_extend . ' where e_status = 0 ';

        if ($foreign_key !== false) {
            $sql .= ' and foreign_key = ?';
            $param_array[] = $foreign_key;
        }
        if ($class !== false) {
            $sql .= ' and e_class = ?';
            $param_array[] = $class;
        }
        if ($sort !== false) {
            $sql .= ' and e_sort = ?';
            $param_array[] = $sort;
        }
        if ($keyword) {
            $sql .= ' and e_title = ?';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($page_page - 1) * $page_pagesize;
        return $this->get_page_list($this, $sql, $param_array, $start, (int)$page_pagesize, $page_order, $page_type);
    }
}