<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/28
 * Time: 11:09
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class CmsModel extends Model
{
    var $a_status; //文章状态

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->table_name = CMS_ARTICLE;
        $this->key_id = 'a_id';

        $this->a_status = array(
            0 =>'删除',
            1 =>'显示',
            2 =>'未发布,不显示',
            3 =>'草稿中'
        );

        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\TagModel', 'tagMod');

        $this->return_status[1] = '文章不存在';
    }

    public function get_article($a_id)
    {

    }

    /**
     * 获到草稿
     * @param $a_id
     * @return array|bool
     */
    public function get_article_draft($a_id)
    {
        $article = $this->get($a_id);
        if($article) {
            $article['draft'] = $this->get($a_id, CMS_ARTICLEDRAFT, 'a_id');
            if($article['draft']) {
                $article['a_title'] = $article['draft']['a_title'];
                $article['a_img'] = $article['draft']['a_img'];
                $article['a_abstract'] = $article['draft']['a_abstract'];
                $article['a_content'] = $article['draft']['a_content'];
                $article['a_extended'] = $article['draft']['a_extended'];
            }
            $article['sort'] =  $this->get_all($a_id, CMS_ARTICLESORT, 'a_id');
            $article['tag'] =  $this->get_all($a_id, CMS_ARTICLETAG, 'a_id');
            return $article;
        } else {
            return new Ret($this->create_article, 1);
        }
    }

    public function create_article_draft($a_id)
    {
        $article_draft = $this->get($a_id, CMS_ARTICLEDRAFT, 'a_id');
        if(!$article_draft) {
            $article = $this->get($a_id);
            if($article) {
                $article_draft = array(
                    'a_id' => $article['a_id'],
                    'user_code' => $article['user_code'],
                    'a_title' => $article['a_title'],
                    'a_img' => $article['a_img'],
                    'a_abstract' => $article['a_abstract'],
                    'a_content' => $article['a_content'],
                    'a_atime' => time(),
                    'a_etime' => time(),
                    'a_extended' => $article['a_extended'],
                );
                $this->add($article_draft, CMS_ARTICLEDRAFT);
            }
        }
        return $article_draft;
    }


    /**
     * 创建一个空文章
     * @param $user_code
     * @return mixed 文章ID
     */
    public function create_article($user_code)
    {
        $new_article = array(
            'user_code' => $user_code,
            'a_atime' => time(),
            'a_etime' => time(),
            'a_status' => 3
        );
        $a_id = $this->add($new_article);

        $article_draft = array(
            'user_code' => $user_code,
            'a_id' => $a_id,
            'a_atime' => time(),
            'a_etime' => time(),
        );
        $this->add($article_draft, CMS_ARTICLEDRAFT);

        return $a_id;
    }

    /**
     * 编辑文章
     * @param $user_code
     * @param $a_id
     * @param $article
     * @param $tag TAG
     * @param $sort 分类
     * @return mixed
     */
    public function edit_article($user_code, $a_id, $article, $tag , $sort)
    {
        $this->log('修改文章:[' . $user_code . ']  [' . $a_id . ']');
        $article_draft = $this->get_article_draft($a_id); // 确保文章草稿存在
        if($article_draft) {
            $article['a_etime'] = time();
            $ret = $this->edit($a_id, CMS_ARTICLEDRAFT, 'a_id');
            $this->manage_tag($a_id, $tag);
            $this->manage_sort($a_id, $sort);
            return $ret;
        } else {
            return new Ret($this->create_article, 1);
        }
    }

    /**
     * 删除草稿
     * @param $a_id
     * @return mixed
     */
    public function del_article_draft($a_id)
    {
        $ret = $this->del($a_id, CMS_ARTICLEDRAFT, 'a_id');
        return $ret;
    }

    /**
     * 删除文章 包括对应的草稿
     * @param $a_id
     * @return mixed
     */
    public function del_article($user_code, $a_id)
    {
        $this->log('删除文章:[' . $user_code . ']  [' . $a_id . ']');
        $this->del_article_draft($a_id);

        $article_data = array(
            'a_etime' => time(),
            'a_status' => 0
        );
        $ret = $this->edit($a_id, $article_data);
        return  $ret;
    }

    /**
     * 发布文章
     * @param $user_code
     * @param $a_id
     * @param bool $a_publish_time
     * @return Ret|mixed
     */
    public function publish_article($user_code, $a_id, $a_publish_time = false) {
        $this->log('发布文章:[' . $user_code . ']  [' . $a_id . ']');
        $article_draft = $this->get($a_id, CMS_ARTICLEDRAFT, 'a_id');
        if($article_draft) {
            $article = array(
                'a_title' => $article_draft['a_title'],
                'a_img' => $article_draft['a_img'],
                'a_abstract' => $article_draft['a_abstract'],
                'a_content' => $article_draft['a_content'],
                'a_etime' => time(),
                'a_extended' => $article_draft['a_extended'],
                'a_publish_time' => ($a_publish_time && $a_publish_time > time())  ? $a_publish_time: 0,
                'a_status' => ($a_publish_time && $a_publish_time > time())  ? 2: 1
            );
            $ret = $this->edit($a_id, $article);

            $this->del_article_draft($a_id); // 删除草稿
            return $ret;
        } else {
            return new Ret($this->return_status, 1);
        }
    }

    /**
     * 处理TAG
     * @param $a_id
     * @param $tag
     */
    public function manage_tag($a_id, $tag) {
        $old_tag_ret = $this->get_all($a_id, CMS_ARTICLETAG, 'a_id');
        $old_tag = array();
        if($old_tag_ret) {
            foreach($old_tag_ret as $old_tag_item) {
                $old_tag[$old_tag_item['ct_name']] = $old_tag_item;
            }
        }

        $new_tag = array();
        if($tag) {
            foreach($tag as $tag_item) {
                $new_tag[$tag_item['ct_name']] = $tag_item;
            }
        }

        if($old_tag) {
            foreach($old_tag as $old_tag_node) {
                if(isset($new_tag[$old_tag_node['ct_name']])) {
                    // 两次都存在，从新数据中剔除
                    unset($new_tag[$old_tag_node['ct_name']]);
                } else {
                    $this->del($old_tag_node['at_id'], CMS_ARTICLETAG, 'at_id');
                }
            }
        }

        if($new_tag) {
            // 新数据中还存在未插入的数据
            foreach($new_tag as $new_tag_node) {
                $data = array(
                    'ct_title' => $new_tag_node['ct_title'],
                    'ct_name' => $new_tag_node['ct_name'],
                    'a_id' => $a_id,
                    'at_order' => $new_tag_node['at_order'],
                );
                $this->add($data, CMS_ARTICLETAG);
            }
        }
    }


    /**
     * 处理分类
     * @param $a_id
     * @param $sort
     */
    public function manage_sort($a_id, $sort) {
        $old_sort_ret = $this->get_all($a_id, CMS_ARTICLESORT, 'a_id');
        $old_sort = array();
        if($old_sort_ret) {
            foreach($old_sort_ret as $old_sort_item) {
                $old_sort[$old_sort_item['cs_name']] = $old_sort_item;
            }
        }

        $new_sort = array();
        if($sort) {
            foreach($sort as $sort_item) {
                $new_sort[$sort_item['cs_name']] = $sort_item;
            }
        }

        if($old_sort) {
            foreach($old_sort as $old_sort_node) {
                if(isset($new_sort[$old_sort_node['cs_name']])) {
                    // 两次都存在，从新数据中剔除
                    unset($new_sort[$old_sort_node['cs_name']]);
                } else {
                    $this->del($old_sort_node['at_id'], CMS_ARTICLESORT, 'at_id');
                }
            }
        }

        if($new_sort) {
            // 新数据中还存在未插入的数据
            foreach($new_sort as $new_sort_node) {
                $data = array(
                    'ct_title' => $new_sort_node['ct_title'],
                    'cs_name' => $new_sort_node['cs_name'],
                    'a_id' => $a_id,
                    'at_order' => $new_sort_node['at_order'],
                );
                $this->add($data, CMS_ARTICLESORT);
            }
        }
    }


}