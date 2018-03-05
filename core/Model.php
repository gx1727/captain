<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 9:54
 */
class Model extends Base
{
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


    function __construct($namespace, $modular)
    {
        parent::__construct($namespace, $modular);

        /**
         * 对模块的返回值进行扩展
         */
        $this->return_status = array();
        $this->return_status[0] = "成功";
        $this->return_status[999] = "无效的返回状态";
    }

    public function query($sql, $param = null, $one = true)
    {
        $this->database(); //初始化数据库连接
        if ($one) {
            return $this->db->rawQueryOne($sql, $param);
        } else {
            return $this->db->rawQuery($sql, $param);
        }
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