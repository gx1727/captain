<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class AuthModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_ROLE;
        $this->key_id = 'role_id';

        $this->return_status[1] = "角色不存在";
        $this->return_status[2] = "";
    }

    public function get_role_list($list_param, $keyword)
    {
        $param_array = array();
        $sql = "from " . CAPTAIN_ROLE;
        if ($keyword !== false) {
            $sql .= ' where (role_name like ?';
            $sql .= ' or role_title like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($list_param['page'] - 1) * $list_param['pagesize'];
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }

    public function role_edit($role_id, $param)
    {
        $role = $this->get($role_id, CAPTAIN_ROLE, 'role_id');
        if ($role) {
            foreach ($role as $key => $val) {
                if (isset($param[$key])) {
                    $role[$key] = $param[$key];
                }
            }
            $ret = $this->edit($role_id, $role, CAPTAIN_ROLE, 'role_id');
            return $ret;
        } else {
            $ret = new Ret($this->return_status);
            $ret->set_code(1);
            return $ret;
        }
    }
}