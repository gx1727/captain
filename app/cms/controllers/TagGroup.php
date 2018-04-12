<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 15:36
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class TagGroup extends Controller
{
    var $user_code;
    var $role_name;

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\TagGroupModel', 'tagGroupMod');
        $this->model('\captain\cms\CmsModel', 'cmsMod');

        $session = &$this->get_session(); // 引用 session
        $this->user_code = $session->get_sess('user_code');
        $this->role_name = $session->get_sess('role_code');

        $this->return_status[1] = '失败';
        $this->return_status[2] = 'TAG不存在';
    }

    /**
     * tag分组列表
     */
    public function tag_group_list()
    {
        $keyword = $this->input->get_post('keyword');
        $taggroup_list_ret = $this->tagGroupMod->get_all_tag_group($keyword);

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
        $ret = $this->tagGroupMod->add_tag_group(array());
        $this->json($this->get_result($ret));
    }

    public function tag_group_edit()
    {
        $ctg_id = $this->input->get_post('ctg_id');
        $post_data = $this->input->get_post('postData');
        $post_data = json_decode($post_data, true);
        $ret = $this->tagGroupMod->tag_group_edit($ctg_id, $post_data);
        $this->cmsMod->refresh_cache(); //刷新cache
        $this->json($this->get_result($ret));
    }

    public function tag_group_del()
    {
        $ctg_id = $this->input->get_post('ctg_id');
        $ret = $this->tagGroupMod->del_tag_group($ctg_id);
        $this->cmsMod->refresh_cache(); //刷新cache
        $this->json($this->get_result($ret));
    }
}