<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-04-30
 * Time: 下午 12:45
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Special extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\SpecialModel', 'specialMod');
        $this->model('\captain\cms\CmsModel', 'cmsMod');
        $this->help('cms_helper'); // 引入 cms_helper

        $this->return_status[1] = '失败';
        $this->return_status[2] = '专栏不存在';
    }

    public function special_list()
    {
        $keyword = $this->input->get_post('keyword');
        $special_list_ret = $this->specialMod->get_special_list($this->get_list_param(), $keyword);

        if ($special_list_ret->get_code() === 0) {
            $special_list = $special_list_ret->get_data();
            foreach ($special_list as &$item) {

                $flag = $this->cmsMod->article_flag_encode($item['s_flag']);
                $flag_title = array();
                foreach ($flag as $flag_name => $flag_node) {
                    if ($flag_node) {
                        $flag_title[] = article_flag($flag_name);
                    }
                }
                $item['flag'] = implode(',', $flag_title);
            }
            $this->json($this->get_result(array('data' => $special_list, 'total' => $special_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }

    public function special_get()
    {
        $s_id = $this->input->get_post('s_id');
        $special = $this->specialMod->get_special($s_id);
        if ($special) {
            $special['flag'] = $this->cmsMod->article_flag_encode($special['s_flag']);
        }
        $this->json($this->get_result($special));
    }

    public function special_form()
    {
        $s_id = $this->input->get_post('s_id');
        $s_name = $this->input->get_post('s_name');
        $s_title = $this->input->get_post('s_title');
        $s_img = $this->input->get_post('s_img');
        $s_abstract = $this->input->get_post('s_abstract');
        $s_content = $this->input->get_post('s_content', '', false);
        $s_extended = $this->input->get_post('s_extended');
        $s_template = $this->input->get_post('s_template');

        $recommend = $this->input->get_post('recommend');
        $top = $this->input->get_post('top');
        $cover = $this->input->get_post('cover');
        $special = $this->input->get_post('special');

        if ($s_id) {
            $data = array(
                's_name' => $s_name,
                's_title' => $s_title,
                's_img' => $s_img,
                's_abstract' => $s_abstract,
                's_content' => $s_content,
                's_extended' => $s_extended,
                's_template' => $s_template,
                's_flag' => $this->cmsMod->article_flag_decode($recommend, $top, $cover, $special),
                's_atime' => time()
            );
            $ret = $this->specialMod->edit_special($s_id, $data);
            $this->json($this->get_result($ret));
        } else {
            $ret = $this->specialMod->create_special($s_name, $s_title, $s_img, $s_abstract, $s_content, $s_extended, $s_template, $this->cmsMod->article_flag_decode($recommend, $top, $cover, $special));
            $this->json($this->get_result($ret));
        }
        $this->cmsMod->refresh_cache();
    }

    public function special_del()
    {
        $s_id = $this->input->get_post('s_id');
        $ret = $this->specialMod->del_special($s_id);
        $this->json($this->get_result($ret));
        $this->cmsMod->refresh_cache();
    }
}