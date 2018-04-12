<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/27
 * Time: 10:02
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Cms extends Controller
{
    var $user_code;
    var $role_name;

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\TagModel', 'tagMod');
        $this->model('\captain\cms\TagGroupModel', 'tagGroupMod');
        $this->model('\captain\cms\CmsModel', 'cmsMod');

        $session = &$this->get_session(); // 引用 session
//
        $this->user_code = $session->get_sess('user_code');
        $this->role_name = $session->get_sess('role_code');

        $this->return_status[1] = '失败';
        $this->return_status[2] = '分类不存在';
    }


    ////////////////////////////////////////////////////////////////////////
    /// 文章操作
    ///
    /**
     * 获到文章、草稿
     */
    public function article_get()
    {
        $a_id = $this->input->get_post('a_id');
        $article = $this->cmsMod->get_article_draft($a_id);
        $this->json($this->get_result($article));
    }

    /**
     * 文章列表
     */
    public function article_list()
    {
        $search_param = $this->get_list_param();
        $search_param['search'] = $this->input->get_post('keyword');
        $article_list_ret = $this->cmsMod->search_article($search_param);
        $this->help('cms_helper'); // 引入 cms_helper

        if ($article_list_ret->get_code() === 0) {
            $article_list = $article_list_ret->get_data();

            $article = array();
            foreach ($article_list as $article_item) {
                $article_item['a_atime'] = date('Y-m-d H:i:s', $article_item['a_atime']);
                $article_item['a_etime'] = date('Y-m-d H:i:s', $article_item['a_etime']);
                $article_item['a_status_title'] = a_status($article_item['a_status']);
                $article[] = $article_item;

            }
            $this->json($this->get_result(array('data' => $article, 'total' => $article_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }

    /**
     * 创建一个空文章
     */
    public function article_create()
    {
        $a_title = $this->input->get_post('a_title');
        $article = $this->cmsMod->create_article($this->user_code, $a_title);
        $this->json($this->get_result($article));
    }

    /**
     * 编辑文章
     */
    public function article_edit()
    {
        $a_id = $this->input->get_post('a_id');
        $a_title = $this->input->get_post('a_title');
        $a_img = $this->input->get_post('a_img');
        $a_abstract = $this->input->get_post('a_abstract');
        $a_content = $this->input->get_post('a_content', '', false);
        $a_extended = $this->input->get_post('a_extended');
        $a_publish_time = $this->input->get_post('a_publish_time');
        $a_recommend = $this->input->get_post('a_recommend');
        $sort = $this->input->get_post('sort');
        $tag = $this->input->get_post('tag');

        $tag_list = array();
        $tag_list_ret = json_decode($tag, true);
        if ($tag_list_ret) {
            foreach ($tag_list_ret as $ct_name) {
                $tag_list[] = $this->tagMod->get_tag_byname($ct_name);
            }
        }
        $sort_list = array();
        $sort_list_ret = json_decode($sort, true);
        if ($sort_list_ret) {
            foreach ($sort_list_ret as $cs_name) {
                $sort_list[] = $this->sortMod->get_sort_byname($cs_name);
            }
        }

        $article = array(
            'a_title' => $a_title,
            'a_img' => $a_img,
            'a_abstract' => $a_abstract,
            'a_content' => $a_content,
            'a_recommend' => $a_recommend,
            'a_extended' => $a_extended
        );

        $ret = $this->cmsMod->edit_article($this->user_code, $a_id, $article, $tag_list, $sort_list);

        $this->json($this->get_result($ret));
    }

    public function article_publish()
    {
        $a_id = $this->input->get_post('a_id');
        $publish_time = $this->input->get_post('publish_time');
        if ($publish_time) {
            $publish_time = strtotime($publish_time);
        }
        $ret = $this->cmsMod->publish_article($this->user_code, $a_id, $publish_time);
        $this->json($this->get_result($ret));
    }

    public function article_del()
    {

    }

    /**
     * 增加次数
     */
    public function plus_article_count()
    {

    }
}