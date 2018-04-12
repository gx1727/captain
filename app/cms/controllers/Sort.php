<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/12
 * Time: 15:34
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Sort extends Controller
{
    var $user_code;
    var $role_name;

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\CmsModel', 'cmsMod');

        $session = &$this->get_session(); // 引用 session
        $this->user_code = $session->get_sess('user_code');
        $this->role_name = $session->get_sess('role_code');

        $this->return_status[1] = '失败';
        $this->return_status[2] = 'TAG不存在';
    }
    /**
     * 获到分类树
     */
    public function get_sort_tree()
    {
        $cs_id = $this->input->get_post('cs_id');
        $role_sort_tree = $this->sortMod->get_sort_data($cs_id);
        if ($role_sort_tree) {
            $this->json($this->get_result($role_sort_tree));
        } else {
            $this->json($this->get_result(false, 1));
        }
    }

    /**
     * 获取分类详细
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
     * 编辑分类
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
            $this->cmsMod->refresh_cache(); //刷新cache
            $this->json($this->get_result($ret));
        } else {
            $ret = $this->sortMod->add_sort($cs_name, $cs_title, $cs_template, $cs_parent, $cs_order, $cs_img);
            $this->cmsMod->refresh_cache(); //刷新cache
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
        $this->cmsMod->refresh_cache(); //刷新cache
        $this->json($this->get_result($ret));
    }

}