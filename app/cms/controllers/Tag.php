<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/12
 * Time: 14:30
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Tag extends Controller
{
    var $user_code;
    var $role_name;

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\TagGroupModel', 'tagGroupMod');
        $this->model('\captain\cms\TagModel', 'tagMod');
        $this->model('\captain\cms\CmsModel', 'cmsMod');

        $session = &$this->get_session(); // 引用 session
        $this->user_code = $session->get_sess('user_code');
        $this->role_name = $session->get_sess('role_code');

        $this->return_status[1] = '失败';
        $this->return_status[2] = 'TAG不存在';
    }

    public function tag_list()
    {
        $keyword = $this->input->get_post('keyword');
        $ctg_name = $this->input->get_post('ctg_name');
        $ctg_name = json_decode($ctg_name, true);
        $tag_list_ret = $this->tagMod->get_tag_list($this->get_list_param(), $ctg_name, $keyword);

        if ($tag_list_ret->get_code() === 0) {
            $tag_list = $tag_list_ret->get_data();
            foreach ($tag_list as &$tag_item) {
                $tag_group = $this->tagGroupMod->get_tag_group_byname($tag_item['ctg_name']);
                $tag_item['ctg_name_title'] = $tag_group['ctg_title'];
                $tag_item['img'] = '<img src="' . $tag_item['ct_img'] . '" style="width: 60px;"/>';

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
        $this->cmsMod->refresh_cache(); //刷新cache
        $this->json($this->get_result($ret));
    }

    public function tag_edit()
    {
        $ct_id = $this->input->get_post('ct_id');
        $post_data = $this->input->get_post('postData');
        $post_data = json_decode($post_data, true);
        $ret = $this->tagMod->tag_edit($ct_id, $post_data);
        $this->cmsMod->refresh_cache(); //刷新cache
        $this->json($this->get_result($ret));
    }

    public function tag_del()
    {
        $ct_id = $this->input->get_post('ct_id');
        $ret = $this->tagMod->del_tag($ct_id);
        $this->cmsMod->refresh_cache(); //刷新cache
        $this->json($this->get_result($ret));
    }

    public function tag_get()
    {
        $ret = $this->tagMod->get_tag_data();
        $this->json($this->get_result(array('tagData' => $ret)));
    }
}