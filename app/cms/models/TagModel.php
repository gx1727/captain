<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/27
 * Time: 14:40
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class TagModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\TagGroupModel', 'tagGroupMod');
        $this->model('\captain\cms\CheckName', 'cnMod');

        $this->table_name = CMS_TAG;
        $this->key_id = 'ct_id';

        $this->library_core('\captain\core\Pinyin', 'pinyinLib');

        $this->return_status[1] = "TAG不存在";
        $this->return_status[3] = "TAG标题不能为空";
        $this->return_status[4] = 'TAG标题已存在，不能重复';
        $this->return_status[5] = 'TAG名称已存在，不能重复';
        $this->return_status[6] = '新增TAG时，自动获取的分类名称有重复，系统默认增加了随机尾缀';
    }

    public function get_tag_byid($ct_id)
    {
        $tag_group = $this->get($ct_id);

        return $tag_group;
    }

    public function get_tag_byname($ct_name)
    {
        $tag = $this->get($ct_name, $this->table_name, 'ct_name');
        return $tag;
    }

    public function get_tag_list($list_param, $ctg_name, $keyword)
    {
        $param_array = array();
        $sql = "from " . $this->table_name . ' where ct_status = 0 ';
        if ($ctg_name) {
            $sql .= ' and (';
            $tag_list = array();
            foreach ($ctg_name as $ctg_name_node) {
                $tag_list[] = 'ctg_name = ?';
                $param_array[] = $ctg_name_node;
            }
            $sql .= implode($tag_list, ' or ');
            $sql .= ') ';
        }
        if ($keyword !== false) {
            $sql .= ' and (ct_name like ?';
            $sql .= ' or ctg_name like ?';
            $sql .= ' or ct_title like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($list_param['page'] - 1) * $list_param['pagesize'];

        if (!$list_param['orderby']) {
            $list_param['orderby'] = 'ct_id';
            $list_param['ordertype'] = 'desc';
        }
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }

    /**
     * 获到所有TAG的key-value
     * @return array
     */
    public function get_alltags($ctg_name = false)
    {
        $sql = 'select * from ' . $this->table_name . ' where ct_status = 0 ';
        $query_param = array();
        if ($ctg_name) {
            $sql .= ' and ctg_name = ?';
            $query_param[] = $ctg_name;
        }
        $sql .= ' order by ct_order desc';

        $all_tags_list = $this->query($sql, $query_param, false);
        $alltags = array();
        foreach ($all_tags_list as $tag) {
            $alltags[$tag['ct_name']] = $tag;
        }
        return $alltags;
    }

    /**
     * @param $ctg_name
     * @param $ct_title
     * @param $ct_template
     * @param $ct_order
     * @param $ct_img
     * @return Ret
     */
    public function add_tag($ctg_name, $ct_title, $ct_template, $ct_order, $ct_img)
    {
        $ret = new Ret($this->return_status);

        if (!$ct_title) {
            $ret->set_code(3);
            return $ret;
        }
        if ($this->check_tag_title($ct_title, 0, $ctg_name)) { // 同一个ctg_name下，不能有同标题  tag
            $ret->set_code(4); // TAG标题已存在，不能重复
            return $ret;
        }

        $ct_name = $this->pinyinLib->py($ct_title);
        $ct_name = $this->cnMod->get_name($ct_name);

        $tag_data = array(
            'ctg_name' => $ctg_name,
            'ct_name' => $ct_name,
            'ct_title' => $ct_title,
            'ct_template' => $ct_template,
            'ct_order' => $ct_order,
            'ct_img' => $ct_img,
            'ct_atime' => time(),
            'ct_etime' => time(),
        );
        $ct_id = $this->add($tag_data);
        $tag_data['ct_id'] = $ct_id;
        $ret->set_data($ct_id);
        $ret->set_result($tag_data);
        return $ret;
    }

    /**
     * 直接插入数据，当有冲突时，直接返回失败
     * @param $ctg_name
     * @param $ct_name
     * @param $ct_title
     * @return mixed
     */
    public function create_tag($ctg_name, $ct_name, $ct_title)
    {
        $ret = new Ret($this->return_status);
        if (!$this->cnMod->check($ct_name)) {
            // rv_name 冲突
            $ret->set_code(5);
            return $ret;
        }

        $tag_data = array(
            'ctg_name' => $ctg_name,
            'ct_name' => $ct_name,
            'ct_title' => $ct_title,
            'ct_template' => '',
            'ct_order' => 0,
            'ct_img' => '',
            'ct_atime' => time(),
            'ct_etime' => time(),
        );
        $ct_id = $this->add($tag_data);
        $ret->set_data($ct_id);
        return $ret;
    }

    /**
     * 编辑TAG数据
     * @param $ct_id
     * @param $param
     * @return Ret|mixed
     */
    public function tag_edit($ct_id, $param)
    {
        $ret = new Ret($this->return_status);

        $tag = $this->get($ct_id);

        if (isset($param['ct_title'])) {
            if (!$param['ct_title']) {
                $ret->set_code(3);
                return $ret;
            }

            if ($this->check_tag_title($param['ct_title'], $ct_id, $tag['ctg_name'])) {
                $ret->set_code(4); // TAG标题已存在，不能重复
                return $ret;
            }
        }

        if (isset($param['ct_name']) && !$this->cnMod->check($param['ct_name'], $this->table_name, $ct_id)) {
            $ret->set_code(5);
            return $ret;
        }


        if ($tag) {
            $old_ct_name = $tag['ct_name']; // 记录原ct_name, 如有变更，要同步修改文章关系
            $old_ct_parent = $tag['ct_parent']; // 记录原ct_parent, 如有变更，要同步修改ct_child值
            foreach ($tag as $key => $val) {
                if (isset($param[$key])) {
                    $tag[$key] = $param[$key];
                }
            }
            $tag['ct_etime'] = time();
            $this->edit($ct_id, $tag);

            // 修改原记录原ct_parent的ct_child值
            if ($old_ct_parent) {
                $this->update_child($old_ct_parent);
            }

            // 修改现记录原ct_parent的ct_child值
            if ($tag['ct_parent'] != $old_ct_parent) {
                $this->update_child($tag['ct_parent']);
            }

            if ($old_ct_name != $tag['ct_name']) { // 有变更
                $sql = 'update cms_article_tag set ct_name = ? where ct_name = ?';
                $this->query($sql, array($tag['ct_name'], $old_ct_name));
            }
            $ret->set_code(0);
            return $ret;
        } else {
            $ret = new Ret($this->return_status);
            $ret->set_code(1);
            return $ret;
        }
    }

    /**
     * 更新指定TAG的下级个数
     * @param $ct_name
     */
    public function update_child($ct_name)
    {
        $sql = 'select count(1) as child_count from ' . $this->table_name . ' where ct_parent = ? and ct_status = 0';
        $child_count_ret = $this->query($sql, array($ct_name));
        $child_count = $child_count_ret['child_count'];
        $this->edit($ct_name, array('ct_child' => $child_count), $this->table_name, 'ct_name');
    }

    /**
     * 删除TAG
     * 同时删除文章-TAG关系数据
     * @param $ct_id
     * @return mixed
     */
    public function del_tag($ct_id)
    {
        $tag = $this->get($ct_id);
        if ($tag) {
            $sql = 'delete from ' . CMS_ARTICLETAG . ' where ct_name = ?';
            $this->query($sql, array($tag['ct_name']));
        }
        return $this->edit($ct_id, array('ct_status' => 1, 'ct_etime' => time()));
    }


    /**
     * 检查是否存在同名的tag
     * @param $ct_title
     * @param int $ct_id
     * @return mixed
     */
    public function check_tag_title($ct_title, $ct_id = 0, $ctg_name = '')
    {
        $query_param = array($ct_title);
        $sql = 'select ct_id from ' . $this->table_name . ' where ct_status = 0 and ct_title = ?';
        if ($ct_id > 0) {
            $sql .= ' and ct_id != ? ';
            $query_param[] = $ct_id;
        }
        if ($ctg_name) {
            $sql .= ' and ctg_name = ? ';
            $query_param[] = $ctg_name;
        }
        $sql .= ' limit 1';
        $tag = $this->query($sql, $query_param);
        return $tag;
    }


    /**
     * 获取所有tag数据
     * 并按TAG分组名分组
     */
    public function get_tag_data($user_code = '')
    {
        $tag_groups = $this->tagGroupMod->get_tag_groups($user_code); // 获到所有TAG分组

        $tags = $this->get_tags($user_code);
        $tag_data = array();
        foreach ($tag_groups as $tag_group_node) {
            $tag_group_node['tagList'] = array();
            $tag_data[$tag_group_node['ctg_name']] = $tag_group_node;
        }

        foreach ($tags as $tags_node) {
            $tag_data[$tags_node['ctg_name']]['tagList'][] = $tags_node;
        }

        return $tag_data;
    }

    /**
     * 获到用户可操作的TAG组
     * @param $user_code
     */
    public function get_tags($user_code)
    {
        $sql = 'select * from ' . CMS_TAG . ' where ct_status = 0 order by ct_order desc';
        $tags = $this->query($sql, null, false);
        return $tags;
    }

    /**
     * 删除文章时,
     * 同时要删除 指定TAG与文章的关系
     * @param int $a_id 文章ID
     * @param string $ct_name TAG名称
     */
    public function del_article($a_id = 0, $ct_name = '')
    {
        if ($a_id) {
            $sql = 'delete from ' . CMS_ARTICLETAG . ' where a_id = ?';
            $this->query($sql, array($a_id));
        }

        if ($ct_name) {
            $sql = 'delete from ' . CMS_ARTICLETAG . ' where ct_name = ?';
            $this->query($sql, array($ct_name));
        }
    }
}