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

class Article extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\CmsModel', 'cmsMod');

        $this->return_status[1] = '失败';
    }

    /**
     * 文章入口
     * @return mixed
     */
    public function index()
    {
        $sorts_tags = $this->cmsMod->get_sorts_tags();
        $this->help_core('view_helper'); // 引入 view_helper
        $this->help('cms_helper'); // 引入 cms_helper

        $param = $this->input->get_uri();
        if (is_numeric($param[0])) { // 文章内页
            $a_id = current($param);
            $article = $this->cmsMod->get_article($a_id);


            $template = ''; // 显示模板
            if ($article['a_template']) {
                $template = $article['a_template'];
            } else {
                // 查找分类中的模板
                $sorts = array();
                if ($article['sorts']) {
                    foreach ($article['sorts'] as $sort_node) {
                        $sorts[] = $sort_node['cs_name'];
                    }
                }
                while (sizeof($sorts) > 0) {
                    $sort_name = array_shift($sorts);
                    if ($sorts_tags[$sort_name]['template']) {
                        $template = $sorts_tags[$sort_name]['template'];
                    } else if ($sorts_tags[$sort_name]['parent']) {
                        $sorts[] = $sorts_tags[$sort_name]['parent'];
                    }
                }
            }
            if (!$template) {
                $template = 'article';
            }

            $this->assign('article', $article);
            $this->view($template);
        } else { // 文件列表
            if (isset($sorts_tags[$param[0]])) {
                $article_param = $sorts_tags[$param[0]];

                $search_param = $this->get_list_param();
                if ($article_param['type'] === 'sort') {
                    $search_param['sorts'] = array($param[0]);
                } else if ($article_param['type'] === 'tag') {
                    $search_param['tags'] = array($param[0]);
                }

                // 有分页
                $page = 1;
                if (isset($param[1]) && is_numeric($param[1])) {
                    $page = $param[1];
                }
                $search_param['page'] = $page;

                $article_list_ret = $this->cmsMod->search_article($search_param);

                $this->assign('$page', $page);
                $this->assign('articles', $article_list_ret->get_data());
                $this->assign('articles_sum', $article_list_ret->get_data(2));
                $this->assign('article_param', $article_param);

                //处理模板
                $template = $article_param['template'] ? $article_param['template'] : 'page';
                $this->view($template);
            }
        }
    }
}