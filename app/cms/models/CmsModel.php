<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/28
 * Time: 11:09
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Rand;

use \captain\core\Model;
use \captain\core\Ret;

class CmsModel extends Model
{
    var $a_status; //文章状态
    var $page_breaks; // 分页符
    var $orderby_arr; //可排序字段

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
        $this->orderby_arr = array(
            'new' => 'a_ptime',
            'hot' => 'a_count'
        );

        $this->page_breaks = '<p>~~~page~~~</p>';

        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\TagModel', 'tagMod');

        $this->return_status[1] = '文章不存在';
        $this->return_status[2] = "文章标题有重复";
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
     * 获到草稿预览
     * @param $a_id
     * @param $index
     * @return array|bool
     */
    public function get_article_preview($a_id, $index)
    {
        $article = false;
        $article_draft = $this->get($a_id, CMS_ARTICLEDRAFT, 'a_id');

        if ($article_draft) {
            $user = $this->get($article_draft['user_code'], CAPTAIN_USER, 'user_code');
            $article_publish = $this->get($a_id, CMS_ARTICLE, 'a_id');
            $flag = $this->article_flag_encode($article_draft['a_flag']);
            $content = '';
            $article = array(
                'a_id' => $article_draft['a_id'],
                'code' => $article_publish['a_code'],
                'title' => $article_draft['a_title'],
                'img' => $article_draft['a_img'],
                'abstract' => $article_draft['a_abstract'],
                'content' => $content,
                'template' => $article_draft['a_template'],
                'a_ptime' => $article_draft['a_etime'],
                'date' => date('Y年m月d日', $article_draft['a_etime']),
                'count' => $article_publish['a_count'],
                'extended' => json_decode($article_draft['a_extended'], true),
                'recommend' => $flag['recommend'], // 是否推荐
                'top' => $flag['top'], //
                'cover' => $flag['cover'], //
                'special' => $flag['special'], //
                'editor' => $user['user_title'], // 发布者
                'user' => $article_draft['user_code'],
                'index' => $index, // 小分页中的当前页
                'sum' => 1,
                'page' => explode($this->page_breaks, $article_draft['a_content'])
            );
            $page_list = explode($this->page_breaks, $article_draft['a_content']);
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

        if ($article) {
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
    public function search_article($search_param)
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
            if (isset($search_param['sorts']) && is_array($search_param['sorts'])) {
                $sorts = $search_param['sorts'];
            }

            // 处理文章tag
            $tags = false;
            if (isset($search_param['tags']) && is_array($search_param['tags'])) {
                $tags = $search_param['tags'];
            }

            $param_array = array();
            $sql = "from " . $this->table_name . " a  where a.a_status = 1 ";

            // 处理搜索关键词
            if (isset($search_param['search']) && $search_param['search']) {
                $sql .= " and a.a_title like '%" . $search_param['search'] . "%'";
            }

            if ($sorts) {
                $in_sorts = array();
                foreach ($sorts as $sort) {
                    $in_sorts[] = '\'' . $sort . '\'';
                }
                $sql .= ' and exists (select 1 from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  in (' . implode(',', $in_sorts) . '))';
            }

            if ($tags) {
                $in_tags = array();
                foreach ($tags as $tag) {
                    $in_tags[] = '\'' . $tag . '\'';
                }
                $sql .= ' and exists (select 1 from ' . CMS_ARTICLETAG . ' `at` where a.a_id = `at`.a_id and `at`.ct_name  in (' . implode(',', $in_tags) . '))';
            }

            $start = ($search_param['page'] - 1) * $search_param['pagesize'];
            return $this->get_page_list($this, $sql, $param_array, $start, (int)$search_param['pagesize'], $search_param['orderby'], $search_param['ordertype']);
        }
        return $ret;
    }


    public function get_article_list($list_param, $keyword, $a_status, $lanmu = '')
    {
        $param_array = array();
        $sql = "from " . CMS_ARTICLE . ' a where a_status > 0 ';
        if ($keyword) {
            $sql .= ' and (a_title like ?';
            $sql .= ' or a_abstract like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        if ($a_status) {
            $sql .= ' and a_status = ?';
            $param_array[] = $a_status;
        }

        if ($lanmu) {
            $sql .= ' and exists (select 1 from ' . CMS_ARTICLESORT . ' `as` where a.a_id = `as`.a_id and `as`.cs_name  = \'' . $lanmu . '\')';
        }

        if (!$list_param['orderby']) {
            $list_param['orderby'] = 'a_ptime';
            $list_param['ordertype'] = 'desc';
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
                $article['a_template'] = $article['draft']['a_template'];
                $article['draft_etime'] = date('Y-m-d H:i:s', $article['draft']['a_etime']); // 草稿最后修改时间
                $article['a_flag'] = $article['draft']['a_flag'];
            } else {
                $article['draft_etime'] = '';
            }
            $article['sort'] = $this->get_all($a_id, CMS_ARTICLESORT, 'a_id');
            $article['tag'] = $this->get_all($a_id, CMS_ARTICLETAG, 'a_id');
            $article['publish_time'] = date('Y-m-d H:i:s', $article['a_etime']); // 文章最后修改时间，即发布时间
            $article['a_publish_time'] = $article['a_publish_time'] > 0 ? date('Y-m-d H:i:s', $article['a_publish_time']) : ''; // 文章最后修改时间，即发布时间
            $article['flag'] = $this->article_flag_encode($article['a_flag']);
            return $article;
        } else {
            return new Ret($this->return_status, 1);
        }
    }

    /**
     * 获到用户最后的草稿
     * @param $user_code
     * @return bool
     */
    public function get_user_draft($user_code)
    {
        $sql = 'select a_id from ' . CMS_ARTICLEDRAFT . ' where user_code = ? order by a_id desc limit 1';
        $article_draft = $this->query($sql, array($user_code));
        if ($article_draft) {
            $sql = 'select a_id from ' . CMS_ARTICLE . ' where a_id > ? and a_status = 1 limit 1';
            $article = $this->query($sql, array($article_draft['a_id']));
            if ($article) {
                // 存在更新的文章ID,放弃本草稿
                $article_draft = false;
            }
        }
        return $article_draft;
    }

    /**
     * 获到文章code
     * @param $a_id
     * @return null|string
     */
    public function create_article_code($a_id)
    {
        $a_code = Rand::encodeCode($a_id, 8);
        while ($a_code < 10000000) {
            $a_code = Rand::encodeCode($a_id, 8);
        }
        $article_tmp = $this->get($a_code, $this->table_name, 'a_code');
        if ($article_tmp) {
            // 如果文章code已存在，就重新生成
            return $this->create_article_code($a_id);
        } else {
            return $a_code;
        }

    }

    /**
     * 创建一个空文章
     * @param $user_code
     * @param string $type auto:自动 会查找最后一个草稿
     * @return array|bool 文章ID
     */
    public function create_article($user_code, $type)
    {
        $article_draft = $this->get_user_draft($user_code);
        if ($article_draft && $type !== 'new') {
            return $this->get_article_draft($article_draft['a_id']);
        } else {
            $new_article = array(
                'user_code' => $user_code,
                'a_code' => 0,
                'a_title' => '',
                'a_atime' => time(),
                'a_etime' => time(),
                'a_status' => 3
            );
            $a_id = $this->add($new_article);


            $this->edit($a_id, array('a_code' => $this->create_article_code($a_id)));
            $article_draft = array(
                'user_code' => $user_code,
                'a_title' => '',
                'a_id' => $a_id,
                'a_atime' => time(),
                'a_etime' => time(),
            );
            $this->add($article_draft, CMS_ARTICLEDRAFT);
            return $this->get_article_draft($a_id);
        }

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

        if(!$this->check_article_title($article['a_title'], $a_id)) {
            $ret->set_code(2);
            return $ret;
        }
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
     * 验证同名项
     * @param $a_title
     * @param int $a_id
     * @return bool
     */
    public function check_article_title($a_title, $a_id = 0)
    {
        $query_param = array($a_title);
        $sql = 'select a_id from ' .$this->table_name. ' where a_status != 0 and a_title = ?';
        if ($a_id > 0) {
            $sql .= ' and a_id  != ? ';
            $query_param[] = $a_id;
        }
        $sql .= ' limit 1';
        $value = $this->query($sql, $query_param);

        if ($value) {
            return false; // 查到同名项
        } else {
            return true; // 没有同名项
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
                'a_flag' => $article_draft['a_flag'],
                'a_extended' => $article_draft['a_extended'],
                'a_template' => $article_draft['a_template'],
                'a_publish_time' => ($a_publish_time && $a_publish_time > time()) ? $a_publish_time : 0,
                'a_status' => ($a_publish_time && $a_publish_time > time()) ? 2 : 1
            );

            //处理首次发布时间
            $article_old = $this->get($a_id);
            if (!$article_old['a_ptime']) {
                $article['a_ptime'] = time();
            }

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
     * 获到编辑人员的文章数据
     * 发布文章数有草稿列表
     * @param $user_code
     * @return array
     */
    public function get_statistics($user_code)
    {
        $ret = array(
            'article' => 0,
            'draft' => 0
        );
        $sql = 'select count(*) as article_count from ' . $this->table_name . ' where user_code = ? and a_status = 1 order by a_id desc';
        $publish_count = $this->query($sql, array($user_code));
        $ret['article'] = $publish_count['article_count'];

        $sql = 'select a_id, user_code, a_title, a_img, a_abstract, a_atime, a_etime, a_flag, a_template from ' . CMS_ARTICLEDRAFT . ' where user_code = ?';
        $ret['draft'] = $this->query($sql, array($user_code), false);

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

    ///////////////////////////////////////////////////////////
    ///
    ///

    /**
     * 获到栏目
     * @return array
     */
    public function get_lanmu()
    {
        $lanmu = array();
        $sorts_tags = $this->get_sorts_tags();
        foreach ($sorts_tags as $sorts_tags_item) {
            if ($sorts_tags_item['parent'] == 'lanmu') {
                $lanmu[$sorts_tags_item['name']] = $sorts_tags_item;
            }
        }
        return $lanmu;
    }

    /**
     * 不要多次调用
     * @return array
     */
    public function get_sorts_tags()
    {
        $cache_file_path = BASEPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cms' .
            DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'sorts_tags.php';
        if (file_exists($cache_file_path)) {
            $sorts_tags = array();
            include $cache_file_path; //防止多次调用时， 返回空值
            return $sorts_tags;
        } else {
            $this->refresh_cache();
            return array();
        }
    }

    /**
     * 刷新缓存
     */
    public function refresh_cache()
    {
        $cache_file_path = BASEPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cms' .
            DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'sorts_tags.php';
        $sorts = $this->get_all('cs_status = 0', CMS_SORT, 'where');
        $specials = $this->get_all('s_status = 0', CMS_SPECIAL, 'where');
        $tags = $this->get_all('ct_status = 0', CMS_TAG, 'where');
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
            $sorts_tags .= '\'article_template\' => \'' . $sort['cs_article_template'] . '\',';
            $sorts_tags .= '\'template\' => \'' . $sort['cs_template'] . '\',';
            $sorts_tags .= '), ';
        }

        foreach ($specials as $special) {
            $sorts_tags .= '\'' . $special['s_name'] . '\' => array (';
            $sorts_tags .= '\'type\' => \'special\',';
            $sorts_tags .= '\'name\' => \'' . $special['s_name'] . '\',';
            $sorts_tags .= '\'title\' => \'' . $special['s_title'] . '\',';
            $sorts_tags .= '\'parent\' => \'\',';
            $sorts_tags .= '\'img\' => \'' . $special['s_img'] . '\',';
            $sorts_tags .= '\'template\' => \'' . $special['s_template'] . '\',';
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