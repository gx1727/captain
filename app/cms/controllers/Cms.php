<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 10:02
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Cms extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\TagModel', 'tagMod');

        $this->return_status[1] = '失败';
        $this->return_status[2] = '分类不存在';
    }

    /**
     * 通过用户角色，获到角色的菜单树
     */
    public function get_tree()
    {
        $cs_id = $this->input->get_post('cs_id');
        $role_sort_tree = $this->sortMod->get_sort_tree($cs_id);
        if ($role_sort_tree) {
            $this->json($this->get_result($role_sort_tree));
        } else {
            $this->json($this->get_result(false, 1));
        }
    }

    /**
     * 获取菜单详细
     */
    public function get_sort()
    {
        $cs_id = $this->input->get_post('cs_id');
        if ($cs_id) {
            $sort = $this->sortMod->get_sort($cs_id);
            $this->json($this->get_result($sort));
        } else {
            $this->json($this->get_result(false, 2));
        }
    }

    /**
     * 编辑菜单
     */
    public function form_sort()
    {
        $cs_id = $this->input->get_post('cs_id');
        $cs_name = $this->input->get_post('cs_name');
        $cs_title = $this->input->get_post('cs_title');
        $cs_template = $this->input->get_post('cs_template');
        $cs_parent = $this->input->get_post('cs_parent');
        $cs_order = $this->input->get_post('cs_order');
        $cs_img = $this->input->get_post('cs_img');
        if ($cs_id) {
            $data = array(
                'cs_name' => $cs_name,
                'cs_title' => $cs_title,
                'cs_template' => $cs_template,
                'cs_parent' => $cs_parent,
                'cs_order' => $cs_order,
                'cs_img' => $cs_img
            );
            $ret = $this->sortMod->edit_sort($cs_id, $data);
            $this->json($this->get_result($ret));
        } else {
            $ret = $this->sortMod->add_sort($cs_name, $cs_title, $cs_template, $cs_parent, $cs_order, $cs_img);
            $this->json($this->get_result($ret));
        }
    }

    /**
     * 删除分类
     */
    public function del_sort()
    {
        $cs_id = $this->input->get_post('cs_id');
        $ret = $this->sortMod->del_sort($cs_id);
        $this->json($this->get_result($ret));
    }

    ////////////////////////////////////////////////////////////////////////
    /// tag分组列表

    /**
     * tag分组列表
     */
    public function tag_group_list()
    {
        $keyword = $this->input->get_post('keyword');
        $taggroup_list_ret = $this->tagMod->get_all_tag_group($keyword);

        if ($taggroup_list_ret->get_code() === 0) {
            $taggroup_list = $taggroup_list_ret->get_data();
            $this->json($this->get_result(array('data' => $taggroup_list, 'total' => $taggroup_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }

    /**
     * 创建一个空的tag分组
     */
    public function tag_group_add()
    {
        $ret = $this->tagMod->add_tag_group(array());
        $this->json($this->get_result($ret));
    }

    public function tag_group_edit()
    {
        $ctg_id = $this->input->get_post('ctg_id');
        $post_data = $this->input->get_post('postData');
        $post_data = json_decode($post_data, true);
        $ret = $this->tagMod->tag_group_edit($ctg_id, $post_data);
        $this->json($this->get_result($ret));
    }

    public function tag_group_del()
    {
        $ctg_id = $this->input->get_post('ctg_id');
        $ret = $this->tagMod->del_tag_group($ctg_id);
        $this->json($this->get_result($ret));
    }

    ////////////////////////////////////////////////////////////////////////
    /// tag列表

    public function tag_list()
    {
        $keyword = $this->input->get_post('keyword');
        $ctg_name = $this->input->get_post('ctg_name');
        $ctg_name = json_decode($ctg_name, true);
        $tag_list_ret = $this->tagMod->get_tag_list($this->get_list_param(), $ctg_name, $keyword);

        if ($tag_list_ret->get_code() === 0) {
            $tag_list = $tag_list_ret->get_data();
            foreach($tag_list as &$tag_item) {
                $tag_group = $this->tagMod->get_tag_group_byname($tag_item['ctg_name']);
                $tag_item['ctg_name_title'] = $tag_group['ctg_title'];

            }
            $this->json($this->get_result(array('data' => $tag_list, 'total' => $tag_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }

    /**
     * 创建一个空的tag
     */
    public function tag_add()
    {
        $ctg_name = $this->input->get_post('ctg_name');
        $ct_title = $this->input->get_post('ct_title');
        $ct_template = $this->input->get_post('ct_template');
        $ct_order = $this->input->get_post('ct_order', 0);
        $ct_img = $this->input->get_post('ct_img');
        $ret = $this->tagMod->add_tag($ctg_name, $ct_title, $ct_template, $ct_order, $ct_img);
        $this->json($this->get_result($ret));
    }

    public function tag_edit()
    {
        $ct_id = $this->input->get_post('ct_id');
        $post_data = $this->input->get_post('postData');
        $post_data = json_decode($post_data, true);
        $ret = $this->tagMod->tag_edit($ct_id, $post_data);
        $this->json($this->get_result($ret));
    }

    public function tag_del()
    {
        $ct_id = $this->input->get_post('ct_id');
        $ret = $this->tagMod->del_tag($ct_id);
        $this->json($this->get_result($ret));
    }

}