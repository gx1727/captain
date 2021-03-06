<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 9:53
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class SortModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->table_name = CMS_SORT;
        $this->key_id = 'cs_id';

        $this->model('\captain\system\AuthModel', 'authMod');

        $this->library_core('\captain\core\Sort', 'sortLib');
        $this->sortLib->init($this, CMS_SORT, 'cs_id', 'cs_parent', 'cs_subindex', 'cs_index', 'cs_title', 'cs_order', 'cs_status');

        $this->library_core('\captain\core\Pinyin', 'pinyinLib');
        $this->model('\captain\cms\CheckName', 'cnMod');

        $this->return_status[1] = '分类不存在';
        $this->return_status[2] = '分类有下级目录，有能删除';
        $this->return_status[3] = '分类标题不能为空';
        $this->return_status[4] = '分类名称不能为空';
        $this->return_status[5] = '分类名称已存在，不能重复';
        $this->return_status[6] = '新增分类时，自动获取的分类名称有重复，系统默认增加了随机尾缀';
        $this->return_status[7] = '分类上级出现回环';
        $this->return_status[8] = '分类有下级，不能直接删除';
    }

    public function get_sort($cs_id, $key = 'cs_id')
    {
        return $this->get($cs_id, $this->table_name, $key);
    }

    public function get_sort_byname($cs_name)
    {
        $sort = $this->get($cs_name, $this->table_name, 'cs_name');
        return $sort;
    }

    /**
     * 增加分类
     * @param $cs_name
     * @param $cs_title
     * @param $cs_article_template
     * @param $cs_template
     * @param $cs_parent
     * @param $cs_order
     * @param $cs_img
     * @return mixed
     */
    public function add_sort($cs_name, $cs_title, $cs_article_template, $cs_template, $cs_parent, $cs_order, $cs_img)
    {
        $ret = new Ret($this->return_status);
        if (!$cs_title) {
            $ret->set_code(3);
            return $ret;
        }
        if (!$cs_name) { // 用户没有输入分类名
            $cs_name = $this->pinyinLib->py($cs_title);
            $cs_name = $this->cnMod->get_name($cs_name);
        } else { // 用户手输了分类名
            if (!$this->cnMod->check($cs_name)) {
                $ret->set_code(5);
                return $ret;
            }
        }
        $data = array(
            'cs_name' => $cs_name,
            'cs_title' => $cs_title,
            'cs_template' => $cs_template,
            'cs_parent' => $cs_parent,
            'cs_order' => $cs_order,
            'cs_img' => $cs_img,
            'cs_atime' => time(),
            'cs_etime' => time(),
            'cs_status' => 0
        );
        $cs_id = $this->add($data);
        $ret->set_data($cs_id);
        return $ret;
    }

    /**
     * 编辑分类
     * @param $cs_id
     * @param $sort_data
     * @return mixed
     */
    public function edit_sort($cs_id, $sort_data)
    {
        $ret = new Ret($this->return_status);
        $old_sort = $this->get($cs_id); // 原分类

        if (!$sort_data['cs_title']) {
            $ret->set_code(3);
            return $ret;
        }
        if (!$sort_data['cs_name']) {
            $ret->set_code(4);
            return $ret;
        }

        //判断 分类名是否冲突
        if (!$this->cnMod->check($sort_data['cs_name'], $this->table_name, $cs_id)) {
            $ret->set_code(5);
            return $ret;
        }

        //判断 分类是否出现回环
        if (!$this->check_sort_parent($cs_id, $sort_data['cs_parent'])) {
            $ret->set_code(7);
            return $ret;
        }

        $sort_data['cs_etime'] = time();
        $this->edit($cs_id, $sort_data);
        $ret->set_data($sort_data);

        if ($sort_data['cs_name'] != $old_sort['cs_name']) { // 记录原cs_name, 如有变更，要同步修改文章关系
            $sql = 'update cms_article_sort set cs_name = ? where cs_name = ?';
            $this->query($sql, array($sort_data['cs_name'], $old_sort['cs_name']));
        }
        return $ret;
    }

    /**
     * 删除分类
     * 同时删除文章-分类关系数据
     * @param $cs_id
     * @return mixed
     */
    public function del_sort($cs_id)
    {
        $sort = $this->get($cs_id);
        if ($sort) {
            //先判断是否有下级分类，若存在下级，不能删除
            $children = $this->sortLib->get_children($cs_id);
            if ($children) {
                return new Ret($this->return_status, 8);
            } else {
                $sql = 'delete from ' . CMS_ARTICLESORT . ' where cs_name = ?';
                $this->query($sql, array($sort['cs_name']));

                $sort['cs_status'] = 1;
                $sort['cs_etime'] = time();
                return $this->edit($cs_id, $sort);
            }
        } else {
            return new Ret($this->return_status, 1);
        }
    }


    /**
     * 获到子级
     * @param $cs_name
     * @return Ret
     */
    public function get_children($cs_name)
    {
        $sort = $this->get($cs_name, $this->table_name, 'cs_name');
        if ($sort) {
            return $this->sortLib->get_children($sort['cs_id']);
        } else {
            return new Ret($this->return_status, 1);
        }
    }


    /**
     * 获到目录树
     * @param $root_id
     * @return array
     */
    public function get_sort_data($root_id, $user_code = '')
    {
        $sortTree = $this->sortLib->get_sort_tree($root_id, 'manage_menu_node', 'children');
        $sortList = $this->sortLib->get_sort_list($root_id);
        return array(
            'sortTree' => $sortTree,
            'sortList' => $sortList
        );
    }


    /**
     * 获到所有分类的key-value
     * @return array
     */
    public function get_allsorts()
    {
        $all_sorts_list = $this->get_all(false);
        $allsorts = array();
        foreach ($all_sorts_list as $sort) {
            $allsorts[$sort['cs_name']] = $sort['cs_title'];
        }
        return $allsorts;
    }

    /**
     * 组合树结点的回调
     * @param $sort_node
     * @return mixed
     */
    public function manage_menu_node($sort_node)
    {
        $sort_node['title'] = $sort_node['cs_title'];
        $sort_node['name'] = $sort_node['cs_name'];
        $sort_node['expand'] = true;
        return $sort_node;
    }

    /**
     * 判断 分类是否出现回环
     * @param $cs_id
     * @param $cs_parent
     * @return bool|int
     */
    public function check_sort_parent($cs_id, $cs_parent)
    {
        //上级为顶级，不会有回环
        if ($cs_parent == 0) {
            return true;
        }

        if ($cs_id == $cs_parent) {
            return false;
        }

        $parent = $this->get($cs_parent);
        if ($parent) {
            return $this->check_sort_parent($cs_id, $parent['cs_parent']);
        } else {
            // 上级ID不存在，出现错误
            return true;
        }
    }

    /**
     * 删除文章对应的Sort关系
     * @param int $a_id 文章ID
     * @param string $cs_name 分类名
     */
    public function del_article($a_id = 0, $cs_name = '')
    {
        if ($a_id) {
            $sql = 'delete from ' . CMS_ARTICLESORT . ' where a_id = ?';
            $this->query($sql, array($a_id));
        }
        if ($cs_name) {
            $sql = 'delete from ' . CMS_ARTICLESORT . ' where cs_name = ?';
            $this->query($sql, array($cs_name));
        }
    }
}