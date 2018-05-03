<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-04-29
 * Time: 上午 8:13
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Template extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\TemplateModel', 'templateMod');
        $this->help('cms_helper'); // 引入 cms_helper

        $this->return_status[1] = '失败';
        $this->return_status[2] = '模板不存在';
    }

    /**
     * 获到模板列表
     */
    public function template_list()
    {
        $keyword = $this->input->get_post('keyword');
        $template_list_ret = $this->templateMod->get_template_list($this->get_list_param(), $keyword);

        if ($template_list_ret->get_code() === 0) {
            $template_list = $template_list_ret->get_data();
            foreach ($template_list as &$item) {
                $item['t_type'] = t_type($item['t_type']);
                $item['t_class'] = t_class($item['t_class']);
            }
            $this->json($this->get_result(array('data' => $template_list, 'total' => $template_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }

    public function template_get()
    {
        $t_id = $this->input->get_post('t_id');
        $template = $this->templateMod->get_template($t_id, true);
        $this->json($this->get_result($template));
    }

    public function template_form()
    {
        $t_id = $this->input->get_post('t_id');
        $t_name = $this->input->get_post('t_name');
        $t_type = $this->input->get_post('t_type');
        $t_title = $this->input->get_post('t_title');
        $t_class = $this->input->get_post('t_class');
        $t_path = $this->input->get_post('t_path');
        $t_des = $this->input->get_post('t_des');
        $t_content = $this->input->get_post('t_content', '', false);
        if ($t_id) {
            $data = array(
                't_name' => $t_name,
                't_title' => $t_title,
                't_type' => $t_type,
                't_class' => $t_class,
                't_path' => $t_path,
                't_des' => $t_des,
                't_content' => $t_content
            );
            $ret = $this->templateMod->edit_template($t_id, $data);
            $this->templateMod->refresh_cache();
            $this->json($this->get_result($ret));
        } else {
            $ret = $this->templateMod->add_template($t_name, $t_title, $t_type, $t_class, $t_path, $t_des, $t_content);
            $this->templateMod->refresh_cache();
            $this->json($this->get_result($ret));
        }
    }

    /**
     * 发布
     */
    public function template_publish()
    {

        $t_id = $this->input->get_post('t_id');
        $ret = $this->templateMod->publish_template($t_id);
        $this->json($this->get_result($ret));
    }

    /**
     * 备份
     */
    public function template_backup()
    {
        $t_id = $this->input->get_post('t_id');
        $ret = $this->templateMod->backup_template($t_id);
        $this->json($this->get_result($ret));
    }

    public function template_del()
    {
        $t_id = $this->input->get_post('t_id');
        $ret = $this->templateMod->del_template($t_id);
        $this->templateMod->refresh_cache();
        $this->json($this->get_result($ret));
    }

    /**
     * 预览
     */
    public function preview()
    {
        $param = $this->input->get_uri();

        $t_id = $param[0];
        $template = $this->templateMod->get_template($t_id, false);
        if ($template['t_type'] == 'T') {
            if ($template['t_class'] == 'A') {
                $article = $this->articleMod->get();
                $this->assign('lanmu', $lanmu);
                $this->assign('article', $article);
                $this->view($template);
            } else if ($template['t_class'] == 'L') {

            } else if ($template['t_class'] == 'S') {

            }
        }
    }

    public function template_select()
    {
        $templates = $this->templateMod->get_all_template();
        $this->json($this->get_result(array('data' => $templates)));
    }
}