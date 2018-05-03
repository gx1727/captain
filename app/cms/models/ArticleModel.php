<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/13
 * Time: 14:46
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Rand;
use \captain\core\Ret;

class ArticleModel extends Model
{
    var $a_status; //文章状态
    var $page_breaks; // 分页符
    var $orderby_arr; //可排序字段

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');

        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\TagGroupModel', 'tagGroupMod');
        $this->model('\captain\cms\TagModel', 'tagMod');

        $this->table_name = CMS_ARTICLE;
        $this->key_id = 'a_id';

        $this->orderby_arr = array(
            'new' => 'a_ptime',
            'hot' => 'a_count'
        );

        $this->page_breaks = '<p>~~~page~~~</p>';

        $this->return_status[1] = '文章不存在';
    }

    /**
     * 所有栏目数据
     */
    public function get_lanmu()
    {
        $lanmu = $this->sortMod->get_children('lanmu');
        return $lanmu;
    }


    /**
     * 转换ID
     * 把混淆ID中转换为真实ID
     * @param $a_id
     * @return mixed
     */
    public function convert_id($a_id)
    {
        return Rand::decodeCode($a_id);
    }


    /**
     * 注：
     * 小分页 以 ###page##分页，分页title为 [title:XXXXXXXXXXXX]
     * @param $a_code
     * @param $index
     * @param int $mode 0:简单 1:简单+tags   2:明细
     * @return array|bool
     */
    public function get_article($a_code, $index, $mode = 2)
    {
        $article = false;
        if ($mode == 0 || $mode == 1) { // 简单 简单+tags
            $sql = 'select a_id, a_code as code, a_title as title, a_img as img, a_abstract as abstract from ' . $this->table_name . ' where a_status = 1 and a_code = ?';

            $article = $this->query($sql, array($a_code));
        } else if ($mode == 2) {
            $article_ret = $this->get($a_code, $this->table_name, 'a_code');

            if ($article_ret) {
                $user = $this->get($article_ret['user_code'], CAPTAIN_USER, 'user_code');

                $flag = $this->article_flag_encode($article_ret['a_flag']);
                $content = '';
                $article = array(
                    'a_id' => $article_ret['a_id'],
                    'code' => $article_ret['a_code'],
                    'title' => $article_ret['a_title'],
                    'img' => $article_ret['a_img'],
                    'abstract' => $article_ret['a_abstract'],
                    'content' => $content,
                    'template' => $article_ret['a_template'],
                    'a_ptime' => $article_ret['a_ptime'],
                    'date' => date('Y年m月d日', $article_ret['a_ptime']),
                    'count' => $article_ret['a_count'],
                    'extended' => json_decode($article_ret['a_extended'], true),
                    'recommend' => $flag['recommend'], // 是否推荐
                    'top' => $flag['top'], //
                    'cover' => $flag['cover'], //
                    'special' => $flag['special'], //
                    'editor' => $user['user_title'], // 发布者
                    'user' => $article_ret['user_code'],
                    'index' => $index, // 小分页中的当前页
                    'sum' => 1,
                    'page' => explode($this->page_breaks, $article_ret['a_content'])
                );
                $page_list = explode($this->page_breaks, $article_ret['a_content']);
                if ($index > sizeof($page_list)) {
                    $index = sizeof($page_list);
                }
                $page = array();
                foreach ($page_list as $page_index => $page_node) {
                    if ($p_title = strpos($page_node, '<p>[title:') !== false) { // 存在小标题
                        $sub_title_index = strpos($page_node, ']');
                        if (($index - 1) === $page_index) {
                            $article['title'] = substr($page_node, $p_title + 10, ($sub_title_index - 10 - $p_title));
                        }
                        $page_node = ltrim(substr($page_node, $sub_title_index + 5));
                        $page[] = $page_node;
                        $content .= $page_node;
                    } else {
                        $page[] = ltrim($page_node);
                        $content .= $page_node;
                    }


                }
                $article['content'] = $content;
                $article['index'] = $index;
                $article['sum'] = sizeof($page);
                $article['page'] = $page;
            }
        }
        if ($article) {
            if ($mode == 1 || $mode == 2) { // sort 和 tag
                $sorts_ret = $this->sortMod->get_all($article['a_id'], CMS_ARTICLESORT, 'a_id');
                $sorts = array();
                if ($sorts_ret) {
                    foreach ($sorts_ret as $sorts_item) {
                        $sorts[$sorts_item['cs_name']] = $sorts_item['cs_title'];
                    }
                }
                $article['sorts'] = $sorts;

                $tags_ret = $this->tagMod->get_all($article['a_id'], CMS_ARTICLETAG, 'a_id');
                $tags = array();
                if ($tags_ret) {
                    foreach ($tags_ret as $tags_item) {
                        $tags[$tags_item['ct_name']] = $tags_item['ct_title'];

                        // 针对封面要特殊处理
                        if ($tags_item['ct_name'] == 'fengmian') {
                            if ($article['sorts']) {
                                foreach ($article['sorts'] as $sort_name => $sort_title) {
                                    $article['url'] = $sort_name;
                                }
                            } else {
                                $article['url'] = '#';
                            }
                        }
                    }
                }
                $article['tags'] = $tags;
            }
        }
        return $article;
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
     * -- 并集思路
     * -- 下面的sql的意思是找到所有技术部年龄大于25的员工
     * SELECT a.* FROM(
     * SELECT id,code,name FROM test_emp WHERE age>25
     * UNION ALL
     * SELECT id,code,name FROM test_emp WHERE dept='JSB'
     * )a GROUP BY a.id HAVING COUNT(a.id)=2
     *
     * @return Ret
     */
    public function page($search_param)
    {
        $ret = new Ret($this->return_status);
        if ($search_param['pagesize'] <= 0) {
            $ret->set_code(1);
            return $ret;
        }
        if (isset($search_param['article']) && $search_param['article']) { // 通过文章ID获到单篇文章
            $ret = $this->get_content($search_param['article']);
        } else {
            // 处理文章分类
            $sorts = false;
            if (isset($search_param['sorts']) && $search_param['sorts']) {
                $sorts = $search_param['sorts'];
            }
            // 处理文章tag
            $tags = false;
            if (isset($search_param['tags']) && $search_param['tags']) {
                $tags = $search_param['tags'];
            }

            $param_array = array();
            $sql = "from " . $this->table_name . " a  where a.a_status = 1 ";

            // 处理搜索关键词
            if (isset($search_param['search']) && $search_param['search']) {
                $sql .= " and a.a_title like '%" . $search_param['search'] . "%'";
            }

            if ($sorts) {
                if (is_array($sorts)) {
                    $in_sorts = array();
                    foreach ($sorts as $sort) {
                        $in_sorts[] = '\'' . $sort . '\'';
                    }
                    $sql .= ' and exists (select 1 from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  in (' . implode(',', $in_sorts) . '))';
                } else {
                    $sql .= ' and exists (select 1 from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  = \'' . $sorts . '\')';
                }
            }

            if ($tags) {
                $tag_and = explode('&', $tags);
                if (sizeof($tag_and) > 1) {
                    $tag_and_sql_sql = array();
                    foreach ($tag_and as $tag_and_item) {
                        $tag_or = explode('|', $tag_and_item);
                        if (sizeof($tag_or) > 1) {
                            // 有多个 或 计算
                            $in_tags = array();
                            foreach ($tag_or as $tag) {
                                $in_tags[] = '\'' . $tag . '\'';
                            }
                            $tag_and_sql_sql[] = 'select distinct a_id from ' . CMS_ARTICLETAG . ' where ct_name in (' . implode(',', $in_tags) . ')';
                        } else {
                            // 只有一个TAG
                            $tag_and_sql_sql[] = 'select distinct a_id from ' . CMS_ARTICLETAG . ' where ct_name = \'' . $tag_and_item . '\'';
                        }

                    }
                    $tag_and_sql = 'select tag_and.a_id from (';
                    $tag_and_sql .= implode(' UNION ALL ', $tag_and_sql_sql);
                    $tag_and_sql .= ') tag_and group by tag_and.a_id having count(tag_and.a_id) = ' . sizeof($tag_and);

                    $sql .= ' and a.a_id in (' . $tag_and_sql . ')';
                } else {
                    $tag_or = explode('|', $tags);
                    if (sizeof($tag_or) > 1) {
                        // 有多个 或 计算
                        $in_tags = array();
                        foreach ($tag_or as $tag) {
                            $in_tags[] = '\'' . $tag . '\'';
                        }
                        $sql .= ' and exists (select 1 from ' . CMS_ARTICLETAG . ' `at` where a.a_id = `at`.a_id and `at`.ct_name  in (' . implode(',', $in_tags) . '))';
                    } else {
                        // 只有一个TAG
                        $sql .= ' and exists (select 1 from ' . CMS_ARTICLETAG . ' `at` where a.a_id = `at`.a_id and `at`.ct_name  = \'' . $tags . '\')';
                    }
                }
            }

            if (!$search_param['orderby']) {
                $search_param['orderby'] = 'a_ptime';
                $search_param['ordertype'] = 'desc';
            }
            $start = ($search_param['page'] - 1) * $search_param['pagesize'];
            return $this->get_page_list($this, $sql, $param_array, $start, (int)$search_param['pagesize'], $search_param['orderby'], $search_param['ordertype'],
                'a_id, a_code as code, a_title as title, a_img as img, a_abstract as abstract, a_ptime');
        }
        return $ret;
    }

    /**
     * 查找top
     * @param int $limit
     * @param $type
     * @param string $sort
     * @return mixed
     */
    public function top($limit, $type, $sort = '')
    {
        $orderby = isset($this->orderby_arr[$type]) ? $this->orderby_arr[$type] : 'new';

        $sql = 'select a_id, a_code as code, a_title as title, a_img as img,  a_abstract as abstract from ' . $this->table_name . ' a where a_status = 1';
        if ($sort) {
            $sql .= ' and exists (select 1 from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  = \'' . $sort . '\')';
        }
        $sql .= ' order by ' . $orderby . ' desc limit ' . $limit;
        $articles = $this->query($sql, false, false);
        return $articles;
    }


    /**
     * @param $code 文章code
     * @param int $count 需要的文章数
     * @param string $sort 分类  可组合 sort1&sort2|sort3
     * @param string $tag TAG 可组合 tag1&tag2|tag3
     * @param string $flag 文章标志 0：否 1：是
     * @param int $mode 数据规模 0: 基础 1：基础+分类TAG  2：详细
     * @param string $orderby 排序
     * @param string $ordertype 排序顺序
     * @return array|bool
     */
    public function search_article($code, $count = 1, $sort = '', $tag = '', $flag = '', $mode = 0, $orderby = 'new', $ordertype = 'desc')
    {
        if (!$code) {
            $sql = 'select a_id, a_code from ' . $this->table_name . ' a where a.a_status = 1';
            if ($flag) {
                $flag_code = $this->get_article_flag($flag);
                if ($flag_code) {
                    $sql .= ' and a_flag in (' . implode(',', $flag_code) . ')';
                }

            }
            if ($sort) {
                $sort_and = explode('&', $sort);
                if (sizeof($sort_and) > 1) {
                    $sort_and_sql_sql = array();
                    foreach ($sort_and as $sort_and_item) {
                        $sort_or = explode('|', $sort_and_item);
                        if (sizeof($sort_or) > 1) {
                            // 有多个 或 计算
                            $in_sorts = array();
                            foreach ($sort_or as $sort) {
                                $in_sorts[] = '\'' . $sort . '\'';
                            }
                            $sort_and_sql_sql[] = 'select distinct a_id from ' . CMS_ARTICLESORT . ' where cs_name in (' . implode(',', $in_sorts) . ')';
                        } else {
                            // 只有一个分类
                            $sort_and_sql_sql[] = 'select distinct a_id from ' . CMS_ARTICLESORT . ' where cs_name = \'' . $sort_and_item . '\'';
                        }

                    }
                    $sort_and_sql = 'select sort_and.a_id from (';
                    $sort_and_sql .= implode(' UNION ALL ', $sort_and_sql_sql);
                    $sort_and_sql .= ') sort_and group by sort_and.a_id having count(sort_and.a_id) = ' . sizeof($sort_and);

                    $sql .= ' and a.a_id in (' . $sort_and_sql . ')';
                } else {
                    $sort_or = explode('|', $sort);
                    if (sizeof($sort_or) > 1) {
                        // 有多个 或 计算
                        $in_sorts = array();
                        foreach ($sort_or as $sort) {
                            $in_sorts[] = '\'' . $sort . '\'';
                        }
                        $sql .= ' and exists (select 1 from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  in (' . implode(',', $in_sorts) . '))';
                    } else {
                        // 只有一个分类
                        $sql .= ' and exists (select 1 from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  = \'' . $sort . '\')';
                    }
                }
            }
            if ($tag) {
                $tag_and = explode('&', $tag);
                if (sizeof($tag_and) > 1) {
                    $tag_and_sql_sql = array();
                    foreach ($tag_and as $tag_and_item) {
                        $tag_or = explode('|', $tag_and_item);
                        if (sizeof($tag_or) > 1) {
                            // 有多个 或 计算
                            $in_tags = array();
                            foreach ($tag_or as $tag) {
                                $in_tags[] = '\'' . $tag . '\'';
                            }
                            $tag_and_sql_sql[] = 'select distinct a_id from ' . CMS_ARTICLETAG . ' where ct_name in (' . implode(',', $in_tags) . ')';
                        } else {
                            // 只有一个TAG
                            $tag_and_sql_sql[] = 'select distinct a_id from ' . CMS_ARTICLETAG . ' where ct_name = \'' . $tag_and_item . '\'';
                        }

                    }
                    $tag_and_sql = 'select tag_and.a_id from (';
                    $tag_and_sql .= implode(' UNION ALL ', $tag_and_sql_sql);
                    $tag_and_sql .= ') tag_and group by tag_and.a_id having count(tag_and.a_id) = ' . sizeof($tag_and);

                    $sql .= ' and a.a_id in (' . $tag_and_sql . ')';
                } else {
                    $tag_or = explode('|', $tag);
                    if (sizeof($tag_or) > 1) {
                        // 有多个 或 计算
                        $in_tags = array();
                        foreach ($tag_or as $tag) {
                            $in_tags[] = '\'' . $tag . '\'';
                        }
                        $sql .= ' and exists (select 1 from ' . CMS_ARTICLETAG . ' `at` where a.a_id = `at`.a_id and `at`.ct_name  in (' . implode(',', $in_tags) . '))';
                    } else {
                        // 只有一个TAG
                        $sql .= ' and exists (select 1 from ' . CMS_ARTICLETAG . ' `at` where a.a_id = `at`.a_id and `at`.ct_name  = \'' . $tag . '\')';
                    }
                }
            }

            $orderby = isset($this->orderby_arr[$orderby]) ? $this->orderby_arr[$orderby] : 'new';
            $sql .= ' order by a.' . $orderby . ' ' . $ordertype . ' limit ' . $count;

            if ($count == 1) {
                // 只要单篇文章
                $obj = $this->query($sql);
                if ($obj) {
                    $code = $obj['a_code'];
                }
            } else {
                // 获取文章列表
                $obj = $this->query($sql, false, false);
                $articles = array();
                foreach ($obj as $item) {
                    $articles[] = $this->get_article($item['a_code'], 1, $mode);
                }
                return $articles; // 返回文章列表
            }
        }
        return $this->get_article($code, 1, $mode); // 返回单篇文章
    }


    public function search_specials($count = 1, $flag = '')
    {
        $sql = 'select s_id, s_name as code, s_title as title, s_img as img, s_abstract as abstract, s_content as content, s_extended from ' . CMS_SPECIAL . ' where s_status = 0 ';
        if ($flag) {
            $flag_code = $this->get_article_flag($flag);
            if ($flag_code) {
                $sql .= ' and s_flag in (' . implode(',', $flag_code) . ')';
            }
        }
        $sql .= ' order by s_id desc limit ' . $count;
        return $this->query($sql, false, false);
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
     * 文章标志 解码
     * @param $flag 数字码
     * @return array
     */
    public function article_flag_encode($flag)
    {
        $ret = array(
            'recommend' => ($flag & 1) ? 1 : 0,
            'top' => ($flag & 2) ? 1 : 0,
            'cover' => ($flag & 4) ? 1 : 0,
            'special' => ($flag & 8) ? 1 : 0
        );
        return $ret;
    }

    public function article_flag_decode($recommend, $top, $cover, $special)
    {
        $flag = 0;
        if ($recommend) {
            $flag += 1;
        }
        if ($top) {
            $flag += 2;
        }
        if ($cover) {
            $flag += 4;
        }
        if ($special) {
            $flag += 8;
        }
        return $flag;
    }

    /**
     * 传入给定的标志名，返回标志code
     * 如： recommend&top|cover&special 返回 11，13，15
     * @param $flag
     * @return array
     */
    public function get_article_flag($flag)
    {
        $flag_and = explode('&', $flag);
        $and = array();
        foreach ($flag_and as $flag_and_item) {
            $ret = array(
                'recommend' => false,
                'top' => false,
                'cover' => false,
                'special' => false
            );
            $flag_or = explode('|', $flag_and_item);
            foreach ($flag_or as $flag_or_item) {
                if (isset($ret[$flag_or_item])) {
                    $ret[$flag_or_item] = true;
                }
            }
            $and[] = $this->article_flag_decode($ret['recommend'], $ret['top'], $ret['cover'], $ret['special']);
        }

        $ret = array();
        for ($i = 1; $i < 16; $i++) {
            $flag = true;
            foreach ($and as $and_item) {
                $flag &= boolval($i & $and_item);
            }
            if ($flag) {
                $ret[] = $i;
            }
        }

        return $ret;
    }
}