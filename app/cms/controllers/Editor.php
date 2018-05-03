<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/13
 * Time: 9:05
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Editor extends Controller
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
        $this->model('\captain\system\UserModel', 'userMod', 'system');
        $this->model('\captain\cms\EditorModel', 'editorMod');

        $session = &$this->get_session(); // 引用 session
//
        $this->user_code = $session->get_sess('user_code');
        $this->role_name = $session->get_sess('role_code');

        $this->return_status[1] = '失败';
        $this->return_status[2] = '注册用户失败';
        $this->return_status[3] = '用户不存在';
    }

    public function get_editor() {
        $user_id = $this->input->get_post('user_id');
        $user = $this->userMod->get($user_id);
        $this->json($this->get_result($user));
    }

    public function alist()
    {
        $search_param = $this->get_list_param();
        $keyword = $this->input->get_post('keyword');
        $editor_list_ret = $this->editorMod->get_editor_list($search_param, $keyword);

        if ($editor_list_ret->get_code() === 0) {
            $editor_list = $editor_list_ret->get_data();

            $editor = array();
            foreach ($editor_list as $editor_item) {
                $editor_item['user_atime'] = date('Y-m-d H:i:s', $editor_item['user_atime']);
                $editor[] = $editor_item;

            }
            $this->json($this->get_result(array('data' => $editor, 'total' => $editor_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }

    public function form()
    {
        $user_id = $this->input->get_post('user_id');
        $user_name = $this->input->get_post('user_name');
        $pwd = $this->input->get_post('user_pwd');
        $user_true_name = $this->input->get_post('user_true_name');
        $user_phone = $this->input->get_post('user_phone');

        $user_pwd = false;
        if ($pwd) {
            $this->library_core('\captain\core\Secret', 'secretLib', 'cms');
            $user_pwd = $this->secretLib->decoding($pwd);
        }
        $user = false;
        if ($user_id) {
            $user = $this->userMod->get($user_id);
        } else {
            $ret = $this->userMod->register_user($user_name, $user_phone, '', '', $user_pwd, 'ROLE00003');
            if ($ret->get_code() === 0) {
                $user_code = $ret->get_data();
                $user = $this->userMod->get_user($user_code);
            } else {
                $this->json($this->get_result(false, 2));
                return;
            }
            $user_pwd = false; // 新建用户， 密码无需再处理
        }

        if ($user) {
            $user_data = array(
                'user_true_name' => $user_true_name,
                'user_true_name' => $user_true_name,
                'user_phone' => $user_phone,
            );
            if ($user_pwd) { // 修改密码
                $user_data['user_pwd'] = $user_pwd;
            }
            $ret = $this->userMod->edit_user($user['user_code'], $user_data);
            $this->json($this->get_result($ret));
        } else {
            $this->json($this->get_result(false, 3));
        }
    }

    /**
     * 获到编辑人员的文章数据
     * 发布文章数有草稿列表
     */
    public function info()
    {
        $ret = $this->cmsMod->get_statistics($this->user_code);
        if($ret['draft']) {
            foreach($ret['draft'] as $index => $draft) {
                $article = $this->cmsMod->get($draft['a_id']);
                if($article['a_status'] == 1) { // 已有发布过的文章
                    $ret['draft'][$index]['a_ptime'] = date('Y-m-d H:i:s', $article['a_ptime']);
                    $ret['draft'][$index]['publish'] = '已发布';
                    $ret['draft'][$index]['a_title'] = '<a href="/' .  $article['a_code'] . '" target="_blank">' . $draft['a_title'] . '</a>';
                } else {
                    $ret['draft'][$index]['a_ptime'] = '';
                    $ret['draft'][$index]['publish'] = '未发布';
                }
                $ret['draft'][$index]['a_etime'] = date('Y-m-d H:i:s', $draft['a_etime']);
            }
        }
        $this->json($this->get_result($ret));
    }
}