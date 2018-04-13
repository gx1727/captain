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
            0 => '删除',
            1 => '显示',
            2 => '未发布,不显示',
            3 => '草稿中'
        );

        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\TagModel', 'tagMod');

        $this->return_status[1] = '文章不存在';
    }

    public function get_article($a_id)
    {
        $article = $this->get($a_id);
        if ($article) {
            $article['sorts'] = $this->sortMod->get_all($article['a_id'], CMS_ARTICLESORT, 'a_id');
            $article['tags'] = $this->tagMod->get_all($article['a_id'], CMS_ARTICLETAG, 'a_id');
        }

        return $article;
    }

    /**
     * 浏览次数加1
     * @param $a_id
     * @return mixed
     */
    public function plus_article_count($a_id)
    {
        $sql = 'update ' . $this->table_name . ' set a_count = a_count + 1 where a_id = ?';
        return $this->query($sql, array($a_id));
    }

    /**
     * @param  $search_param = array(
     * 'search' => '搜索关键词',
     * 'article' => '指定文章的ID',
     * 'sorts' => array(
     * '分类名'
     * ),
     * 'tags' => array(
     * 'tag名'
     * ),
     * 'page' => '当前页',
     * 'pagesize' => '分页大小',
     * 'orderby' => '',
     * 'ordertype' => ''
     * );
     *
     * @return Ret
     */
    public function search_article($search_param)
    {
        $ret = new Ret($this->return_status);
        if ($search_param['pagesize'] <= 0) {
            $ret->set_code(1);
            return $ret;
        }
        if (isset($search_param['article']) && $search_param['article']) {
            $ret = $this->get_content($search_param['article']);
        } else {
            $sorts = false;
            if (isset($search_param['sorts']) && is_array($search_param['sorts'])) {
                $sorts = $search_param['sorts'];
            }
            $tags = false;
            if (isset($search_param['tags']) && is_array($search_param['tags'])) {
                $tags = $search_param['tags'];
            }

            $param_array = array();
            $sql = "from " . $this->table_name . " a  where a.a_status = 1 ";

            if (isset($search_param['search']) && $search_param['search']) {
                $sql .= " and a.a_title like '%" . $search_param['search'] . "%'";
            }

            if ($sorts) {
                $in_sorts = array();
                foreach ($sorts as $sort) {
                    $in_sorts[] = '\'' . $sort . '\'';
                }
                $sql .= ' and exists (select * from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  in (' . implode(',', $in_sorts) . '))';
            }

            if ($tags) {
                $in_tags = array();
                foreach ($tags as $tag) {
                    $in_tags[] = '\'' . $tag . '\'';
                }
                $sql .= ' and exists (select * from ' . CMS_ARTICLETAG . ' `at` where a.a_id = `at`.a_id and `at`.ct_name  in (' . implode(',', $in_tags) . '))';
            }

            $start = ($search_param['page'] - 1) * $search_param['pagesize'];

            return $this->get_page_list($this, $sql, $param_array, $start, (int)$search_param['pagesize'], $search_param['orderby'], $search_param['ordertype']);
        }
        return $ret;
    }


    public function get_article_list($list_param, $keyword)
    {
        $param_array = array();
        $sql = "from " . CMS_ARTICLE . ' where a_status > 0 ';
        if ($keyword !== false) {
            $sql .= ' and (a_title like ?';
            $sql .= ' or a_abstract like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($list_param['page'] - 1) * $list_param['pagesize'];
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }

    /**
     * 获到草稿
     * @param $a_id
     * @return array|bool
     */
    public function get_article_draft($a_id)
    {
        $article = $this->get($a_id);
        if ($article) {
            $article['draft'] = $this->get($a_id, CMS_ARTICLEDRAFT, 'a_id');
            if ($article['draft']) {
                $article['a_title'] = $article['draft']['a_title'];
                $article['a_img'] = $article['draft']['a_img'];
                $article['a_abstract'] = $article['draft']['a_abstract'];
                $article['a_content'] = $article['draft']['a_content'];
                $article['a_extended'] = $article['draft']['a_extended'];
                $article['draft_etime'] = date('Y-m-d H:i:s', $article['draft']['a_etime']); // 草稿最后修改时间
            } else {
                $article['draft_etime'] = '';
            }
            $article['sort'] = $this->get_all($a_id, CMS_ARTICLESORT, 'a_id');
            $article['tag'] = $this->get_all($a_id, CMS_ARTICLETAG, 'a_id');
            $article['publish_time'] = date('Y-m-d H:i:s', $article['a_etime']); // 文章最后修改时间，即发布时间
            $article['a_publish_time'] = $article['a_publish_time'] > 0 ? date('Y-m-d H:i:s', $article['a_publish_time']) : ''; // 文章最后修改时间，即发布时间
            return $article;
        } else {
            return new Ret($this->return_status, 1);
        }
    }


    /**
     * 创建一个空文章
     * @param $user_code
     * @param $a_title
     * @return mixed 文章ID
     */
    public function create_article($user_code, $a_title)
    {
        $new_article = array(
            'user_code' => $user_code,
            'a_title' => $a_title,
            'a_atime' => time(),
            'a_etime' => time(),
            'a_status' => 3
        );
        $a_id = $this->add($new_article);

        $article_draft = array(
            'user_code' => $user_code,
            'a_title' => $a_title,
            'a_id' => $a_id,
            'a_atime' => time(),
            'a_etime' => time(),
        );
        $this->add($article_draft, CMS_ARTICLEDRAFT);

        return $this->get_article_draft($a_id);
    }

    /**
     * 获取、创建文章对应的草稿
     * @param $a_id
     * @return array|bool
     */
    public function create_article_draft($a_id)
    {
        $article_draft = $this->get($a_id, CMS_ARTICLEDRAFT, 'a_id');
        if (!$article_draft) {
            $article = $this->get($a_id);
            if ($article) {
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
     * 编辑文章
     * @param $user_code
     * @param $a_id
     * @param $article
     * @param $tag TAG
     * @param $sort 分类
     * @return mixed
     */
    public function edit_article($user_code, $a_id, $article, $tag, $sort)
    {
        $ret = new Ret($this->return_status);
        $this->log('修改文章:[' . $user_code . ']  [' . $a_id . ']');
        $article_draft = $this->create_article_draft($a_id); // 确保文章草稿存在
        if ($article_draft) {
            $article['a_etime'] = time();
            $this->edit($a_id, $article, CMS_ARTICLEDRAFT, 'a_id');
            $this->manage_tag($a_id, $tag);
            $this->manage_sort($a_id, $sort);
            $ret->set_result($this->get_article_draft($a_id));
            $ret->set_code(0);
        } else {
            $ret->set_code(1);

        }
        return $ret;
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
        $this->sortMod->del_article($a_id); // 同时要删除 指定文章与sort的关系
        $this->tagMod->del_article($a_id); // 同时要删除 指定文章与tag的关系
        return $ret;
    }

    /**
     * 发布文章
     * @param $user_code
     * @param $a_id
     * @param bool $a_publish_time
     * @return Ret|mixed
     */
    public function publish_article($user_code, $a_id, $a_publish_time = false)
    {
        $ret = new Ret($this->return_status);
        $this->log('发布文章:[' . $user_code . ']  [' . $a_id . ']');
        $article_draft = $this->get($a_id, CMS_ARTICLEDRAFT, 'a_id');
        if ($article_draft) {
            $article = array(
                'a_title' => $article_draft['a_title'],
                'a_img' => $article_draft['a_img'],
                'a_abstract' => $article_draft['a_abstract'],
                'a_content' => $article_draft['a_content'],
                'a_etime' => time(),
                'a_recommend' => $article_draft['a_recommend'],
                'a_extended' => $article_draft['a_extended'],
                'a_publish_time' => ($a_publish_time && $a_publish_time > time()) ? $a_publish_time : 0,
                'a_status' => ($a_publish_time && $a_publish_time > time()) ? 2 : 1
            );
            $this->edit($a_id, $article);

            $this->del_article_draft($a_id); // 删除草稿
            $ret->set_result($this->get_article_draft($a_id));
            $ret->set_code(0);
        } else {
            $ret->set_code(1);
        }
        return $ret;
    }

    /**
     * 处理TAG
     * @param $a_id
     * @param $tag
     */
    public function manage_tag($a_id, $tag)
    {
        $old_tag_ret = $this->get_all($a_id, CMS_ARTICLETAG, 'a_id');
        $old_tag = array();
        if ($old_tag_ret) {
            foreach ($old_tag_ret as $old_tag_item) {
                $old_tag[$old_tag_item['ct_name']] = $old_tag_item;
            }
        }

        $new_tag = array();
        if ($tag) {
            foreach ($tag as $tag_item) {
                $new_tag[$tag_item['ct_name']] = $tag_item;
            }
        }

        if ($old_tag) {
            foreach ($old_tag as $old_tag_node) {
                if (isset($new_tag[$old_tag_node['ct_name']])) {
                    // 两次都存在，从新数据中剔除
                    unset($new_tag[$old_tag_node['ct_name']]);
                } else {
                    $this->del($old_tag_node['at_id'], CMS_ARTICLETAG, 'at_id');
                }
            }
        }

        if ($new_tag) {
            // 新数据中还存在未插入的数据
            foreach ($new_tag as $new_tag_node) {
                $data = array(
                    'ct_title' => $new_tag_node['ct_title'],
                    'ct_name' => $new_tag_node['ct_name'],
                    'a_id' => $a_id,
                    'at_order' => 0,
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
    public function manage_sort($a_id, $sort)
    {
        $old_sort_ret = $this->get_all($a_id, CMS_ARTICLESORT, 'a_id');
        $old_sort = array();
        if ($old_sort_ret) {
            foreach ($old_sort_ret as $old_sort_item) {
                $old_sort[$old_sort_item['cs_name']] = $old_sort_item;
            }
        }

        $new_sort = array();
        if ($sort) {
            foreach ($sort as $sort_item) {
                $new_sort[$sort_item['cs_name']] = $sort_item;
            }
        }

        if ($old_sort) {
            foreach ($old_sort as $old_sort_node) {
                if (isset($new_sort[$old_sort_node['cs_name']])) {
                    // 两次都存在，从新数据中剔除
                    unset($new_sort[$old_sort_node['cs_name']]);
                } else {
                    $this->del($old_sort_node['as_id'], CMS_ARTICLESORT, 'as_id');
                }
            }
        }

        if ($new_sort) {
            // 新数据中还存在未插入的数据
            foreach ($new_sort as $new_sort_node) {
                $data = array(
                    'cs_title' => $new_sort_node['cs_title'],
                    'cs_name' => $new_sort_node['cs_name'],
                    'a_id' => $a_id,
                    'as_order' => 0,
                );
                $this->add($data, CMS_ARTICLESORT);
            }
        }
    }

    ///////////////////////////////////////////////////////////
    ///

    public function get_sorts_tags()
    {
        $sorts_tags = array();
        $cache_file_path = BASEPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cms' .
            DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'sorts_tags.php';
        if (file_exists($cache_file_path)) {
            include_once $cache_file_path;
        } else {
            $this->refresh_cache();
        }
        return $sorts_tags;
    }

    /**
     * 刷新缓存
     */
    public function refresh_cache()
    {
        $cache_file_path = BASEPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cms' .
            DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'sorts_tags.php';
        $sorts = $this->get_all(false, CMS_SORT);
        $tag_groups = $this->get_all(false, CMS_TAGGROUP);
        $tags = $this->get_all(false, CMS_TAG);
        $sorts_list = array();
        foreach ($sorts as $sort) {
            $sorts_list[$sort['cs_id']] = $sort;
        }
        $sorts_tags = '<?php ';
        $sorts_tags .= '$sorts_tags = array (';
        foreach ($sorts as $sort) {
            $sorts_tags .= '\'' . $sort['cs_name'] . '\' => array (';
            $sorts_tags .= '\'type\' => \'sort\',';
            $sorts_tags .= '\'name\' => \'' . $sort['cs_name'] . '\',';
            $sorts_tags .= '\'title\' => \'' . $sort['cs_title'] . '\',';
            $sorts_tags .= '\'parent\' => \'' . (isset($sorts_list[$sort['cs_parent']]) ? $sorts_list[$sort['cs_parent']]['cs_name'] : '') . '\',';
            $sorts_tags .= '\'img\' => \'' . $sort['cs_img'] . '\',';
            $sorts_tags .= '\'template\' => \'' . $sort['cs_template'] . '\',';
            $sorts_tags .= '), ';
        }

        foreach ($tags as $tag) {
            $sorts_tags .= '\'' . $tag['ct_name'] . '\' => array (';
            $sorts_tags .= '\'type\' => \'tag\',';
            $sorts_tags .= '\'name\' => \'' . $tag['ct_name'] . '\',';
            $sorts_tags .= '\'title\' => \'' . $tag['ct_title'] . '\',';
            $sorts_tags .= '\'parent\' => \'' . $tag['ctg_name'] . '\',';
            $sorts_tags .= '\'img\' => \'' . $tag['ct_img'] . '\',';
            $sorts_tags .= '\'template\' => \'' . $tag['ct_template'] . '\',';
            $sorts_tags .= '), ';
        }
        $sorts_tags .= '); ';

        file_put_contents($cache_file_path, $sorts_tags);

    }


}