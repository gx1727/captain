<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-03-09
 * Time: 下午 10:25
 */

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

class Sort
{
    var $mod;
    var $table_name;
    var $key_id;
    var $parent;
    var $subindex;
    var $index;
    var $title;
    var $status; // 有效状态
    var $prefix; // 拼接时的分隔符


    /**
     * Sort constructor.
     */
    function __construct()
    {
        $this->prefix = ' / ';
    }

    /**
     * @param $_mod
     * @param $_table_name 表名
     * @param string $_key_id 键
     * @param string $_parent 父结点的字段名
     * @param string $_subindex 最小的index字段名
     * @param string $_index 自身的index字段名
     * @param string $_title 标题字段名
     * @param string $_order 排序字段名
     * @param string $_status 有效状态段名
     */
    public function init(&$_mod, $_table_name, $_key_id = 'id', $_parent = 'parent', $_subindex = 'subindex', $_index = 'index', $_title = 'title', $_order = 'order', $_status = '')
    {
        $this->mod = $_mod;
        $this->table_name = $_table_name;
        $this->key_id = $_key_id;
        $this->parent = $_parent;
        $this->subindex = $_subindex;
        $this->index = $_index;
        $this->title = $_title;
        $this->order = $_order;
        $this->status = $_status;

        $this->return_status = $this->mod->return_status;
    }

    /**
     * 设置分隔符
     * @param $prefix
     */
    public function set_prefix($prefix)
    {
        $this->prefix = $prefix;
    }

    ////////////////////////////////////////////////////
    //节点编辑操作
    /**
     * 插入一个叶子结点
     * @param $id
     * @param $parent_id
     * @return mixed
     */
    public function insert_node($id, $parent_id)
    {
        $parent = $this->_get_sort($parent_id);

        $nodedata = array();
        if ($parent) {
            $nodedata[$this->parent] = $parent[$this->key_id];

            //父节点的index给子节点
            $nodedata[$this->index] = $parent[$this->index];

            //叶子节点 subindex = index
            $nodedata[$this->subindex] = $nodedata[$this->index];

            //处理父节点以及其它节点
            $this->_increment_sortlist_gt($parent);

        } else {
            //父节点不存在，那么当前节点即为根节点，也为叶子节点
            $nodedata[$this->parent] = 0;
            $nodedata[$this->subindex] = 1;
            $nodedata[$this->index] = 1;
        }
        return $this->mod->edit($id, $nodedata, $this->table_name, $this->key_id);
    }

    /**
     * 删除一个叶子结点
     * 注意，只能删除叶子结点
     * @param $id
     */
    public function remove_node($id)
    {
        $sort = $this->_get_sort($id);
        if ($sort && $sort[$this->subindex] == $sort[$this->index]) {
            //只能删除叶子节点
            $this->_reduction_sortlist_gt($sort);
        }
    }

    /**
     * 获取分类
     * @param $id
     * @return mixed
     */
    private function _get_sort($id)
    {
        return $this->mod->get($id, $this->table_name, $this->key_id);
    }


    /**
     * 父节点的自身index加1
     * 比父节点原 `index` 大的所有节点的 `index` 加 1
     * @param $parent
     */
    private function _increment_sortlist_gt($parent)
    {
        $sort_list = $this->_get_sortlist_gt($parent[$this->index]);
        if ($sort_list) {
            foreach ($sort_list as $node) {
                $this->_node_increment($node);
            }
        }

        $parent[$this->index]++;
        return $this->mod->edit($parent[$this->key_id], $parent, $this->table_name, $this->key_id);
    }

    /**
     * 所有比本节点的`index`大的节点的`index` 减 1
     * 比父节点原 `index` 大的所有节点的 `index` 减 1
     * @param $sort_node
     */
    private function _reduction_sortlist_gt($sort_node)
    {
        $sort_list = $this->_get_sortlist_gt($sort_node[[$this->index]]);
        if ($sort_list) {
            foreach ($sort_list as $node) {
                $this->_node_reduction($node);
            }
        }
    }

    /**
     * 获取比$index大的所有结点
     * @param $index
     * @return array
     */
    private function _get_sortlist_gt($index)
    {
        $sql = "select * from " . $this->table_name . " where " . $this->index . " > ?";
        $rows = $this->mod->query($sql, array($index), false);
        return $rows;
    }

    /**
     * 自增
     * @param $node
     */
    private function _node_increment($node)
    {
        if ($node[$this->index] == $node[$this->subindex]) {
            //判断是叶子节点
            $node[$this->subindex]++;
        }
        $node[$this->index]++;
        return $this->mod->edit($node[$this->key_id], $node, $this->table_name, $this->key_id);
    }

    /**
     * 自减
     * @param $node
     */
    private function _node_reduction($node)
    {
        if ($node[$this->index] == $node[$this->subindex]) {
            //判断是叶子节点
            $node[$this->subindex]--;
        }
        $node[$this->index]--;
        return $this->mod->edit($node[$this->key_id], $node, $this->table_name, $this->key_id);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    //tree 处理

    /**
     * 获取所有分类的树状结构
     * @param int $root
     * @param bool $fun_create_node
     * @return array
     */
    public function get_sort_tree($root = 0, $fun_create_node = false, $children_name = 'children')
    {
        $sort_list_db = array();//所有分类的k-v列表

        $sql = 'select * from ' . $this->table_name;
        if ($this->status) {
            $sql .= ' where ' . $this->status . ' = 0 ';
        }
        $sql .= ' order by ? desc ';

        $query_list = $this->mod->query($sql, array($this->order), false);

        if ($query_list) {
            foreach ($query_list as $sort_node) {
                $sort_list_db[$sort_node[$this->key_id]] = $sort_node;
            }
        }

        $sort_tree = array();
        if ($sort_list_db) {
            foreach ($sort_list_db as $sort_node) {
                if ($sort_node[$this->parent] > 0 && $sort_node[$this->parent] != $sort_node[$this->key_id]) {
                    $sort_list_db[$sort_node[$this->parent]][$children_name][$sort_node[$this->key_id]] = $sort_node[$this->key_id];
                }
            }

            foreach ($sort_list_db as $sort_id => $sort_node) {
                if ($sort_node[$this->parent] == $root) {
                    $this->_create_tree($sort_tree[], $sort_list_db, $sort_id, $fun_create_node, $children_name);
                }
            }
        }

        return $sort_tree;
    }

    private function _create_tree(&$sort_tree, &$sort_list_db, $sort_id, $fun_create_node, $children_name = 'children')
    {
        $sort_node = $sort_list_db[$sort_id];
        if (isset($sort_node[$children_name]) && $sort_node[$children_name]) {
            $children = $sort_node[$children_name];
            unset($sort_node[$children_name]);
            foreach ($children as $childern_sort_id) {
                $this->_create_tree($sort_node[$children_name][], $sort_list_db, $childern_sort_id, $fun_create_node);
            }
        }

        if ($fun_create_node) {
            $sort_node = $this->mod->$fun_create_node($sort_node);
        }
        $sort_tree = $sort_node;
    }


    /////////////////////////////////////////////////
    //子级分类有排缩进效果
    /**
     *  获取分类的列表结构，子级分类有排缩进效果
     * @param int $root
     * @return Ret
     */
    public function get_sort_list($root = 0)
    {
        $sort_list = array();
        $sort_tree = $this->get_sort_tree($root);

        foreach ($sort_tree as $sort_tree_node) {
            $this->_create_list_fromtree($sort_tree_node, $sort_list, 0);
        }

        return $sort_list;
    }

    private function _create_list_fromtree($sort_tree, &$sort_list, $level, $title_prefix = '')
    {
        $nodedata = array(
            'sort_id' => $sort_tree[$this->key_id],
            'level' => $level,
            'local_title' => $sort_tree[$this->title],
            'title' => $title_prefix . $sort_tree[$this->title]
        );

        $sort_list[$sort_tree[$this->key_id]] = $nodedata;
        if (isset($sort_tree['children'])) {
            foreach ($sort_tree['children'] as $childern_sort) {
                $this->_create_list_fromtree($childern_sort, $sort_list, $level + 1, $title_prefix . $sort_tree[$this->title] . $this->prefix);
            }
        }
    }
}