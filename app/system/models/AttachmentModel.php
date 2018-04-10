<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 15:01
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;
use \captain\core\Rand;

class AttachmentModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_ATTACHMENT;
        $this->key_id = 'att_id';
    }

    public function get_attachment($att_id) {
        return $this->get($att_id);
    }


    public function get_role_list($list_param, $user_code = '', $att_class = 1, $att_type = false,
                                  $keyword = false, $start_time = false, $end_time = false)
    {
        $param_array = array();
        $sql = "from " . $this->table_name . ' where att_status = 0 ';
        if($user_code) {
            $sql .= ' and (user_code = \'\' or user_code = ?) ';
            $param_array[] = $user_code;
        } else {
            $sql .= ' and user_code = \'\'';
        }

        if($att_class) {
            $sql .= ' and att_class = ?';
            $param_array[] = $att_class;
        }

        if($att_type) {
            $sql .= ' and att_type = ?';
            $param_array[] = $att_type;
        }

        if ($keyword !== false) {
            $sql .= ' and att_originalname like ?';
            $param_array[] = '%' . $keyword . '%';
        }

        if($start_time) {
            $sql .= ' and att_atime >= ?';
            $param_array[] = $start_time;
        }

        if($end_time) {
            $sql .= ' and att_atime <= ?';
            $param_array[] = $end_time;
        }

        $start = ($list_param['page'] - 1) * $list_param['pagesize'];
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }

    public function add_attachment($user_code, $att_domain, $att_filename, $att_originalname, $att_class, $att_type, $att_size, $att_filepath)
    {
        $data = array(
            'user_code' => $user_code ? $user_code : '',
            'att_domain' => $att_domain,
            'att_filename' => $att_filename,
            'att_originalname' => $att_originalname,
            'att_class' => $att_class,
            'att_type' => $att_type,
            'att_size' => $att_size,
            'att_filepath' => $att_filepath,
            'att_atime' => time(),
            'att_status' => 0
        );
        return $this->add($data);
    }

    public function edit_attachment($att_id, $att_data)
    {
        return $this->edit($att_id, $att_data);
    }

    public function del_attachment($att_id)
    {
        return $this->edit_attachment($att_id, array('att_status' => 1));
    }

}