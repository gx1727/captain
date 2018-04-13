<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/13
 * Time: 9:10
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class EditorModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->table_name = CMS_ARTICLE;
        $this->key_id = 'a_id';


        $this->model('\captain\cms\SortModel', 'sortMod');
        $this->model('\captain\cms\TagModel', 'tagMod');

        $this->return_status[1] = '';
    }

    public function get_editor_list($list_param, $keyword = false)
    {
        $param_array = array();
        $sql = "from " . CAPTAIN_USER . " u, " . CAPTAIN_USERROLE . " ur where u.user_code = ur.user_code and ur.ur_role_code = 'ROLE00003' and u.user_status = 0";
        if ($keyword) {
            $sql .= ' and (u.user_name like ?';
            $sql .= ' or u.user_true_name like ?';
            $sql .= ' or u.user_phone like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($list_param['page'] - 1) * $list_param['pagesize'];
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }
}