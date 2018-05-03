<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-04-30
 * Time: 下午 12:46
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class SpecialModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\CheckName', 'cnMod');

        $this->table_name = CMS_SPECIAL;
        $this->key_id = 's_id';

        $this->return_status[1] = "专题不存在";
        $this->return_status[2] = "名称已被占用，不能新建该专题";
    }

    public function get_special($s_id)
    {
        return $this->get($s_id);
    }

    public function get_special_byname($s_name)
    {
        $sql = 'select s_id, s_name as code, s_title as title, s_img as img, s_abstract as abstract, s_content as content, s_extended from ' . $this->table_name . ' where s_status = 0 and s_name = ?';

        $special = $this->query($sql, array($s_name));
        return $special;
    }

    public function get_special_list($list_param, $keyword)
    {
        $param_array = array();
        $sql = "from " . $this->table_name . ' where s_status = 0 ';
        if ($keyword) {
            $sql .= ' and (s_title like ?';
            $sql .= ' or s_abstract like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }


        if (!$list_param['orderby']) {
            $list_param['orderby'] = 's_id';
            $list_param['ordertype'] = 'desc';
        }
        $start = ($list_param['page'] - 1) * $list_param['pagesize'];
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }

    public function create_special($s_name, $s_title, $s_img, $s_abstract, $s_content, $s_extended, $s_template, $s_flag)
    {
        if (!$this->cnMod->check($s_name)) {
            // s_name 冲突
            return (new Ret($this->return_status, 2));
        } else {
            $data = array(
                's_name' => $s_name,
                's_title' => $s_title,
                's_img' => $s_img,
                's_abstract' => $s_abstract,
                's_content' => $s_content,
                's_extended' => $s_extended,
                's_template' => $s_template,
                's_flag' => $s_flag,
                's_atime' => time()
            );
            return $this->add($data);
        }
    }

    public function edit_special($s_id, $data)
    {
        if (!$this->cnMod->check($data['s_name'], $this->table_name, $s_id)) {
            // s_name 冲突
            return (new Ret($this->return_status, 2));
        } else {
            return $this->edit($s_id, $data);
        }
    }

    public function del_special($s_id)
    {
        return $this->edit($s_id, array(
            's_status' => 1
        ));
    }
}