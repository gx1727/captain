<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/2
 * Time: 14:22
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;
use \captain\core\Rand;

class Article extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\ArticleModel', 'articleMod');
        $this->model('\captain\cms\CmsModel', 'cmsMod');
        $this->model('\captain\cms\TagModel', 'tagMod');
        $this->model('\captain\cms\SpecialModel', 'specialMod');
        $this->model('\captain\cms\TemplateModel', 'templateMod');

        $this->help('cms_helper'); // 引入 cms_helper

        $this->return_status[1] = '失败';
    }

    /**
     * 首页
     */
    public function welcome()
    {
        $templates = $this->templateMod->get_templates(); // 所有模板列表

        $template = 'index';
        if (isset($templates[$template])) {
            $template = $templates[$template];
        }
        $this->view($template);
    }

    /**
     * 文章入口
     * @return mixed
     */
    public function index()
    {
        $sorts_tags = $this->cmsMod->get_sorts_tags();
        $templates = $this->templateMod->get_templates(); // 所有模板列表

        $this->help_core('view_helper'); // 引入 view_helper
        $this->help('cms_helper'); // 引入 cms_helper

        // 栏目
        $lanmu = array(
            'type' => '',
            'name' => '',
            'title' => '',
            'parent' => '',
            'img' => '',
            'template' => ''
        );
        $lanmu_lib = $this->cmsMod->get_lanmu(); // 获到栏目名

        $param = $this->input->get_uri();
        $param_1 = $param[0];
        $param_arr = explode('_', $param_1);
        if (is_numeric($param_arr[0])) { // 文章内页
            $a_code = $param_arr[0]; // 文章ID
            $index = sizeof($param_arr) > 1 ? $param_arr[1] : 1;  // 小分页
            $article = $this->articleMod->get_article($a_code, $index);

            if ($article) {
                // 查找栏目
                $article['lanmu'] = $lanmu;
                foreach ($article['sorts'] as $sort_name => $sort_title) {
                    if (isset($lanmu_lib[$sort_name])) {
                        $lanmu = $lanmu_lib[$sort_name];
                        $article['lanmu'] = $lanmu;
                    }
                }

                $this->articleMod->plus_article_count($article['a_id']); // 查看次数增加
                $template = ''; // 显示模板
                if ($article['template']) { // 文章自带模板
                    $template = $article['template'];
                } else {
                    // 查找分类中的模板
                    $sorts = array();
                    if ($article['sorts']) {
                        foreach ($article['sorts'] as $sort_name => $sort_title) {
                            $sorts[] = $sort_name;
                        }
                    }

                    while (sizeof($sorts) > 0) {
                        $sort_name = array_shift($sorts);
                        if ($sorts_tags[$sort_name]['article_template']) {
                            $template = $sorts_tags[$sort_name]['article_template'];
                            break;
                        } else if ($sorts_tags[$sort_name]['parent']) {
                            $sorts[] = $sorts_tags[$sort_name]['parent'];
                        }
                    }
                }
                if (!$template) {
                    $template = 'article';
                }
                if (isset($templates[$template])) {
                    $template = $templates[$template];
                }

                $this->assign('lanmu', $lanmu);
                $this->assign('article', $article);
                $this->view($template);
            } else {
                //文章不存在
                echo 'no article';
                exit;
            }
        } else { // 文章列表
            if (isset($sorts_tags[$param[0]])) {
                $lanmu = $sorts_tags[$param[0]];
                $article_param = $sorts_tags[$param[0]];

                $search_param = $this->get_list_param();
                if ($article_param['type'] === 'sort') {
                    if ($article_param['name'] == 'camp') { // 如果是营地，需要查看下级目录
                        $search_param['sorts'] = array('camp');
                        foreach ($sorts_tags as $sorts_tags_item) {
                            if ($sorts_tags_item['parent'] == 'camp') {
                                $search_param['sorts'][] = $sorts_tags_item['name'];
                            }
                        }
                    } else {
                        $search_param['sorts'] = $article_param['name'];
                    }
                } else if ($article_param['type'] === 'tag') {
                    $search_param['tags'] = $article_param['name'];
                } else if ($article_param['type'] === 'special') { // 专题
                    $special = $this->specialMod->get_special_byname($article_param['name']);
                    $this->assign('special', $special); // 专题数据
                    $search_param['tags'] = $special['s_extended'];
                }

                // 有分页
                $index = 1;
                if (isset($param[1]) && is_numeric($param[1])) {
                    $index = $param[1];
                }
                $search_param['page'] = $index;

                $article_list_ret = $this->articleMod->page($search_param);

                $articles = $article_list_ret->get_data();
                if ($articles) {
                    foreach ($articles as $i => $article_item) {
                        $tags_ret = $this->tagMod->get_all($article_item['a_id'], CMS_ARTICLETAG, 'a_id');
                        $tags = array();
                        if ($tags_ret) {
                            foreach ($tags_ret as $tags_item) {
                                $tags[$tags_item['ct_name']] = $tags_item['ct_title'];
                            }
                        }
                        $articles[$i]['date'] = date('Y-m-d', $article_item['ptime']);
                        $articles[$i]['tags'] = $tags;
                    }
                }

                $articles_sum = $article_list_ret->get_data(2); // 文章总数
                $this->assign('index', $index);
                $this->assign('articles', $articles);
                $this->assign('sum', ceil($articles_sum / $search_param['pagesize']));
                $this->assign('article_param', $article_param);
                $this->assign('lanmu', $lanmu);

                //处理模板
                $template = $article_param['template'] ? $article_param['template'] : 'page';

                if (isset($templates[$template])) {
                    $template = $templates[$template];
                }
                $this->view($template);
            } else {
                echo 'no list';
            }
        }
    }

    /**
     * 预览
     */
    public function preview()
    {
        $sorts_tags = $this->cmsMod->get_sorts_tags();
        $this->help_core('view_helper'); // 引入 view_helper
        $this->help('cms_helper'); // 引入 cms_helper
        $lanmu = $this->cmsMod->get_lanmu(); // 获到栏目名

        $param = $this->input->get_uri();
        $param_1 = $param[0];
        $param_arr = explode('_', $param_1);
        if (is_numeric($param_arr[0])) { // 文章内页
            $a_code = $param_arr[0]; // 文章ID
            $index = sizeof($param_arr) > 1 ? $param_arr[1] : 1;  // 小分页
            $article = $this->cmsMod->get_article_preview($a_code, $index);

            if ($article) {
                $template = ''; // 显示模板
                if ($article['template']) { // 文章自带模板
                    $template = $article['template'];
                } else {
                    // 查找分类中的模板
                    $sorts = array();
                    if ($article['sorts']) {
                        foreach ($article['sorts'] as $sort_name => $sort_title) {
                            $sorts[] = $sort_name;
                        }
                    }

                    $article['lanmu'] = false;
                    while (sizeof($sorts) > 0) {
                        $sort_name = array_shift($sorts);
                        if ($sorts_tags[$sort_name]['template']) {
                            $template = $sorts_tags[$sort_name]['template'];
                            break;
                        } else if ($sorts_tags[$sort_name]['parent']) {
                            $sorts[] = $sorts_tags[$sort_name]['parent'];
                        }
                        if (isset($lanmu[$sort_name])) {
                            $article['lanmu'] = $lanmu[$sort_name];
                        }
                    }
                }
                if (!$template) {
                    $template = 'article';
                }

                $this->assign('article', $article);
                $this->view($template);
            } else {
                //文章不存在
                echo 'no article';
                exit;
            }
        }
    }

    /**
     * 增加次数
     */
    public function plus_article_count()
    {
        $a_id = $this->input->get_post('a_id');
        $this->cmsMod->plus_article_count($a_id);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    /// 组件

    /**
     * 栏目
     * 组件
     *
     */
    public function lanmuComponent()
    {
        $args = func_get_args(); // 获取参数
        $current_lanmu = isset($args[0]) ? $args[0] : '';

        if (!$current_lanmu) {
            $param = $this->input->get_uri();
            $current_lanmu = $param[0];
        }

        $lanmu = $this->articleMod->get_lanmu();
        $index_flag = true;
        foreach ($lanmu as $lanmu_item) {
            if ($lanmu_item['cs_name'] === $current_lanmu) {
                $index_flag = false;
                break;
            }
        }
        array_unshift($lanmu, array(
            'cs_name' => '',
            'cs_title' => '首页'
        ));
        if ($index_flag) {
            $current_lanmu = '';
        }
        $this->assign('lanmu', $lanmu);
        $this->assign('current_lanmu', $current_lanmu);

        return $this->view('component/lanmu', true);
    }

    /**
     * 底部
     * 组件
     * @return bool|string
     */
    public function footerComponent()
    {
        $args = func_get_args(); // 获取参数
        return $this->view('component/footer', true);
    }

    public function pagingComponent()
    {
        $args = func_get_args(); // 获取参数
        $base = $args[0];
        $index = $args[1];
        $sum = $args[2];
        $this->assign('base', $base);
        $this->assign('index', $index);
        $this->assign('sum', $sum);
        if ($sum >= 1) {
            return $this->view('component/paging', true);
        }
    }

    /**
     *
     * 最新的文章
     * @return array|bool|string
     * $limit 数量， 默认 5 个
     * $type 类型  new:最新  hot:最热
     * $lanmu 栏目名， 为空时为所有
     * $get_flag 是否返回值  1： 返回值  0：直接显示
     */
    public function topComponent()
    {
        $args = func_get_args(); // 获取参数
        $limit = isset($args[0]) ? $args[0] : 5;
        $type = isset($args[1]) ? $args[1] : 'new';
        $lanmu = isset($args[2]) ? $args[2] : '';
        $get_flag = isset($args[3]) ? $args[3] : 0;

        $articles = $this->articleMod->top($limit, $type, $lanmu);
        if ($get_flag) {
            return $articles;
        } else {
            $this->assign('articles', $articles);
            return $this->view('component/top', true);
        }
    }


    /**
     * 获取单篇文章
     */
    public function articleComponent()
    {
        $args = func_get_args(); // 获取参数
        $code = isset($args[0]) ? $args[0] : '';
        $sort = isset($args[1]) ? $args[1] : '';
        $tag = isset($args[2]) ? $args[2] : '';
        $flag = isset($args[3]) ? $args[3] : '';
        $mode = isset($args[4]) ? $args[4] : 2;
        $orderby = isset($args[5]) ? $args[5] : 'new';
        $ordertype = isset($args[6]) ? $args[6] : 'desc';
        return $this->articleMod->search_article($code, 1, $sort, $tag, $flag, $mode, $orderby, $ordertype);
    }

    /**
     * 获取文章列表
     */
    public function articlesComponent()
    {
        $args = func_get_args(); // 获取参数
        $count = isset($args[0]) ? $args[0] : 1;
        $sort = isset($args[1]) ? $args[1] : '';
        $tag = isset($args[2]) ? $args[2] : '';
        $flag = isset($args[3]) ? $args[3] : '';
        $mode = isset($args[4]) ? $args[4] : 0;
        $orderby = isset($args[5]) ? $args[5] : 'new';
        $ordertype = isset($args[6]) ? $args[6] : 'desc';

        return $this->articleMod->search_article(false, $count, $sort, $tag, $flag, $mode, $orderby, $ordertype);
    }

    public function specialsComponent()
    {
        $args = func_get_args(); // 获取参数
        $count = isset($args[0]) ? $args[0] : 1;
        $flag = isset($args[1]) ? $args[1] : '';
        return $this->articleMod->search_specials($count, $flag);
    }
}