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
        $this->table_name = CMS_TAG;
        $this->key_id = 'ct_id';

        $this->library_core('\captain\core\Pinyin', 'pinyinLib');
        $this->library_core('\captain\core\Rand', 'randLib');

        $this->return_status[1] = "TAG分组不存在";
        $this->return_status[3] = "TAG标题不能为空";
        $this->return_status[5] = 'TAG名称已存在，不能重复';
        $this->return_status[6] = '新增TAG时，自动获取的分类名称有重复，系统默认增加了随机尾缀';
    }

    //////////////////////////////////////////////////////////
    /// CMS_TAG
    public function get_tag_byid($ct_id)
    {
        $tag_group = $this->get($ct_id);

        return $tag_group;
    }

    public function get_tag_byname($ct_name)
    {
        $tag_group = $this->get($ct_name, CMS_TAG, 'ct_name');
        return $tag_group;
    }

    public function get_tag_list($list_param, $ctg_name, $keyword)
    {
        $param_array = array();
        $sql = "from " . CMS_TAG . ' where ct_status = 0 ';
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
            $sql .= ' or ct_name like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($list_param['page'] - 1) * $list_param['pagesize'];
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }

    public function add_tag($ctg_name, $ct_title, $ct_template, $ct_order, $ct_img)
    {
        $ret = new Ret($this->return_status);
        if (!$ct_title) {
            $ret->set_code(3);
            return $ret;
        }

        $ct_name = $this->pinyinLib->py($ct_title);
        while ($this->check_tag_name($ct_name)) {
            $ret->set_code(6);
            $ct_name .= $this->randLib->getRandChar(4);
        }


        $data = array(
            'ctg_name' => $ctg_name,
            'ct_name' => $ct_name,
            'ct_title' => $ct_title,
            'ct_template' => $ct_template,
            'ct_order' => $ct_order,
            'ct_img' => $ct_img,
            'ct_atime' => time(),
            'ct_etime' => time(),
        );
        $ct_id = $this->add($data);
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
        if (isset($param['ct_name']) && $this->check_tag_name($param['ct_name'], $ct_id)) {
            $ret->set_code(5);
            return $ret;
        }
        $tag = $this->get($ct_id, CMS_TAG, 'ct_id');
        if ($tag) {
            foreach ($tag as $key => $val) {
                if (isset($param[$key])) {
                    $tag[$key] = $param[$key];
                }
            }
            $tag['ct_etime'] = time();
            $ret = $this->edit($ct_id, $tag, CMS_TAG, 'ct_id');
            return $ret;
        } else {
            $ret = new Ret($this->return_status);
            $ret->set_code(1);
            return $ret;
        }
    }

    public function del_tag($ct_id)
    {
        return $this->edit($ct_id, array('ct_status' => 1, 'ct_etime' => time()), CMS_TAG, 'ct_id');
    }

    /**
     * 判断 分类名是否冲突
     * @param $ct_name
     * @param $ct_id
     * @return int
     */
    public function check_tag_name($ct_name, $ct_id = 0)
    {
        $query_param = array($ct_name);
        $sql = 'select ct_id from ' . $this->table_name . ' where ct_status = 0 and ct_name = ?';
        if ($ct_id > 0) {
            $sql .= ' and ct_id != ? ';
            $query_param[] = $ct_id;
        }
        $sql .= ' limit 1';
        $tag = $this->query($sql, $query_param);
        if ($tag) {
            return $tag['ct_id'];
        } else {

            $sql = 'select cs_id from ' . CMS_SORT . ' where cs_status = 0 and cs_name = ? limit 1';
            $sort = $this->query($sql, array($ct_name));
            if ($sort) {
                return $sort['cs_id'];
            } else {
                return 0;
            }
        }
    }

    //////////////////////////////////////////////////////////
    /// CMS_TAGGROUP
    public function get_tag_group_byid($ctg_id)
    {
        $tag_group = $this->get($ctg_id, CMS_TAGGROUP, 'ctg_id');

        return $tag_group;
    }

    public function get_tag_group_byname($ctg_name)
    {
        $tag_group = $this->get($ctg_name, CMS_TAGGROUP, 'ctg_name');
        return $tag_group;
    }

    public function get_all_tag_group($keyword)
    {
        $param_array = array();
        $sql = "from " . CMS_TAGGROUP . ' where ctg_status = 0';
        if ($keyword !== false) {
            $sql .= ' and (ctg_name like ?';
            $sql .= ' or ctg_title like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        return $this->get_page_list($this->return_status, $sql, $param_array, 0, 999, false, false);
    }

    public function add_tag_group($data)
    {
        $data['ctg_atime'] = time();
        $data['ctg_etime'] = time();
        $this->add($data, CMS_TAGGROUP);
    }

    /**
     * 编辑TAG分组数据
     * @param $ctg_id
     * @param $param
     * @return Ret|mixed
     */
    public function tag_group_edit($ctg_id, $param)
    {
        $tag_group = $this->get($ctg_id, CMS_TAGGROUP, 'ctg_id');
        if ($tag_group) {
            foreach ($tag_group as $key => $val) {
                if (isset($param[$key])) {
                    $tag_group[$key] = $param[$key];
                }
            }
            $tag_group['ctg_etime'] = time();
            $ret = $this->edit($ctg_id, $tag_group, CMS_TAGGROUP, 'ctg_id');
            return $ret;
        } else {
            $ret = new Ret($this->return_status);
            $ret->set_code(1);
            return $ret;
        }
    }

    public function del_tag_group($ctg_id)
    {
        return $this->edit($ctg_id, array('ctg_status' => 1, 'ctg_etime' => time()), CMS_TAGGROUP, 'ctg_id');
    }
}