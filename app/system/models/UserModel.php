<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 10:10
 */

use \captain\core\Model;

class UserModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_USER;
        $this->key_id = 'user_id';
    }

    public function test()
    {
        $this->database();
    }

    public function get_user_list($page_page, $page_pagesize, $page_order = false, $page_type = false, $keyword = false)
    {
        $param_array = array();
        $sql = "from " . CAPTAIN_USER . " where user_status = 0";
        if ($keyword !== false) {
            $sql .= ' and (user_code like ?';
            $sql .= ' or user_name like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($page_page - 1) * $page_pagesize;
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$page_pagesize, $page_order, $page_type);
    }
}