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
        $this->model('\captain\system\UserModel', 'userMod', 'system');
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
        $keyword = $this->input->get_post('keyword');
        $a_status = $this->input->get_post('a_status');
        $lanmu = $this->input->get_post('lanmu');

        $article_list_ret = $this->cmsMod->get_article_list($search_param, $keyword, $a_status, $lanmu);
        $this->help('cms_helper'); // 引入 cms_helper

        if ($article_list_ret->get_code() === 0) {
            $article_list = $article_list_ret->get_data();

            $lanmu = $this->cmsMod->get_lanmu(); // 获到栏目名

            $article = array();
            foreach ($article_list as $article_item) {
                $user = $this->userMod->get_user($article_item['user_code']);
                $sorts = $this->sortMod->get_all($article_item['a_id'], CMS_ARTICLESORT, 'a_id');
                $article_item['lanmu'] = array();
                $article_item['sorts'] = array();
                foreach ($sorts as $sort) {
                    if (isset($lanmu[$sort['cs_name']])) {
                        $article_item['lanmu'][] = $sort['cs_title'];
                    } else {
                        $article_item['sorts'][] = $sort['cs_title'];
                    }
                }
                $article_item['lanmu'] = implode(',', $article_item['lanmu']);
                $article_item['sorts'] = implode(',', $article_item['sorts']);
                $article_item['tags'] = array();
                $tags = $this->tagMod->get_all($article_item['a_id'], CMS_ARTICLETAG, 'a_id');
                foreach ($tags as $tag) {
                    $article_item['tags'][] = $tag['ct_title'];
                }
                $article_item['tags'] = implode(',', $article_item['tags']);

                $article_item['a_ptime'] = date('Y-m-d H:i:s', $article_item['a_ptime']);
                $article_item['a_etime'] = date('Y-m-d H:i:s', $article_item['a_etime']);
                $article_item['a_status_title'] = a_status($article_item['a_status']);
                $article_item['user_name'] = $user['user_name'];

                if($article_item['a_status'] == 3) { // 草稿中
                    $draft = $this->cmsMod->get_article_draft($article_item['a_id']);
                    $article_item['a_title'] = $draft['a_title'];
                }
                $flag = $this->cmsMod->article_flag_encode($article_item['a_flag']);

                $flag_title = array();
                foreach($flag as $flag_name => $flag_node) {
                    if($flag_node) {
                        $flag_title[] = article_flag($flag_name);
                    }
                }

                $article_item['flag'] = implode(',', $flag_title);
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
        $type = $this->input->get_post('type');
        $article = $this->cmsMod->create_article($this->user_code, $type);
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
        $a_template = $this->input->get_post('a_template');

        $recommend = $this->input->get_post('recommend');
        $top = $this->input->get_post('top');
        $cover = $this->input->get_post('cover');
        $special = $this->input->get_post('special');

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
            'a_flag' => $this->cmsMod->article_flag_decode($recommend, $top, $cover, $special),
            'a_extended' => $a_extended,
            'a_template' => $a_template,
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

    /**
     * 删除文章
     */
    public function article_del()
    {
        $a_id = $this->input->get_post('a_id');
        $this->cmsMod->del_article($this->user_code, $a_id);
        $this->json($this->get_result($a_id));
    }

    /**
     * 删除草稿
     * 如果文章已发布，只删除草稿
     * 如果文章未发布，删除所有
     */
    public function article_draft_del()
    {
        $a_id = $this->input->get_post('a_id');
        $argicle = $this->cmsMod->get($a_id);
        if ($argicle) {
            if ($argicle['a_status'] == 1) { //如果文章已发布，只删除草稿
                $this->cmsMod->del_article_draft($a_id);
            } else {  //如果文章未发布，删除所有
                $this->cmsMod->del_article($this->user_code, $a_id);
            }
        }
        $this->json($this->get_result($a_id));
    }
}