<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 15:00
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Attachment extends Controller
{
    var $user_code;
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $session = &$this->get_session(); // 引用 session
        $this->user_code = $session->get_sess('user_code');
        $this->role_name = $session->get_sess('role_code');

        $this->model('\captain\system\AttachmentModel', 'attMod');
        $this->return_status[1] = '上传文件失败';
        $this->return_status[1] = '保存文件数据失败';
    }

    /**
     * 上传文件
     */
    public function upload ()
    {
        $this->library('\captain\system\Upload', 'uploadLib');
        $fie_info = $this->uploadLib->handlePost($_FILES['file']);
        if($fie_info['error'] === 0) {
            $att_id = $this->attMod->add_attachment($this->user_code, static_domain(), $fie_info['filename'], $fie_info['originalname'],  $fie_info['class'], $fie_info['type'],  $fie_info['size'], $fie_info['filepath']);
            if($att_id) {
                $att = $this->attMod->get_attachment($att_id);
                $this->json($this->get_result($att));
            } else {
                $this->json($this->get_result(false, 2));
            }
        } else {
            $this->json($this->get_result(false, $fie_info['error']));
        }
    }

    public function alist()
    {
        $search_param = $this->get_list_param();
        $keyword = $this->input->get_post('keyword');
        $att_class = $this->input->get_post('att_class', 1);
        $att_type = $this->input->get_post('att_type');
        $start_time = $this->input->get_post('start_time');
        $end_time = $this->input->get_post('end_time');

        $att_list_ret = $this->attMod->get_role_list($search_param, $this->user_code, $att_class, $att_type, $keyword, $start_time, $end_time);

        if ($att_list_ret->get_code() === 0) {
            $att_list = $att_list_ret->get_data();
            $atts = array();
            foreach ($att_list as $att_item) {
                $att_item['thumbnail'] = '<img src="' . static_domain() .  ($att_item['att_thumbnail'] ? $att_item['att_thumbnail'] : $att_item['att_filepath']) . '" style="height: 60px;">';
                $att_item['att_atime'] = date('Y-m-d H:i:s', $att_item['att_atime']);
                $att_item['id'] = $att_item['att_id'];
                $att_item['name'] = $att_item['att_originalname'];
                $att_item['url'] = $att_item['att_thumbnail'] ? $att_item['att_thumbnail'] : ( $att_item['att_domain'] . $att_item['att_filepath']);
                $att_item['size'] = $att_item['att_size'] < 1024 ? ( $att_item['att_size'] . ' B') : ( $att_item['att_size'] < 1024 * 1024 ? round($att_item['att_size'] / 1024, 2) . 'KB' : (round($att_item['att_size'] / (1024 * 1024), 2) . ' MB') );
                $atts[] = $att_item;
            }
            $this->json($this->get_result(array('data' => $atts, 'total' => $att_list_ret->get_data(2))));
        } else {
            $this->json($this->get_result(array('data' => array(), 'total' => 0)));
        }
    }
}