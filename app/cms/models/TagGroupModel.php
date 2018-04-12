<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/27
 * Time: 14:40
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class TagGroupModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->table_name = CMS_TAGGROUP;
        $this->key_id = 'ctg_id';

        $this->library_core('\captain\core\Pinyin', 'pinyinLib');
        $this->library_core('\captain\core\Rand', 'randLib');

        $this->return_status[1] = "TAG分组不存在";
    }

    public function get_tag_group_byid($ctg_id)
    {
        $tag_group = $this->get($ctg_id);

        return $tag_group;
    }

    public function get_tag_group_byname($ctg_name)
    {
        $tag_group = $this->get($ctg_name, $this->table_name, 'ctg_name');
        return $tag_group;
    }

    /**
     * 获到所有TAG分组
     * @param $keyword
     * @return Ret
     */
    public function get_all_tag_group($keyword)
    {
        $param_array = array();
        $sql = "from " . $this->table_name . ' where ctg_status = 0';
        if ($keyword !== false) {
            $sql .= ' and (ctg_name like ?';
            $sql .= ' or ctg_title like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        return $this->get_page_list($this->return_status, $sql, $param_array, 0, 999, 'ctg_order', 'desc');
    }

    /**
     * 新增TAG分组
     * @param $data
     */
    public function add_tag_group($data)
    {
        $data['ctg_atime'] = time();
        $data['ctg_etime'] = time();
        $this->add($data);
    }

    /**
     * 编辑TAG分组数据
     * @param $ctg_id
     * @param $param
     * @return Ret|mixed
     */
    public function tag_group_edit($ctg_id, $param)
    {
        $tag_group = $this->get($ctg_id, $this->table_name, 'ctg_id');
        if ($tag_group) {
            foreach ($tag_group as $key => $val) {
                if (isset($param[$key])) {
                    $tag_group[$key] = $param[$key];
                }
            }
            $tag_group['ctg_etime'] = time();
            $ret = $this->edit($ctg_id, $tag_group);
            return $ret;
        } else {
            $ret = new Ret($this->return_status);
            $ret->set_code(1);
            return $ret;
        }
    }

    /**
     * 删除TAG分组
     * @param $ctg_id
     * @return mixed
     */
    public function del_tag_group($ctg_id)
    {
        return $this->edit($ctg_id, array('ctg_status' => 1, 'ctg_etime' => time()));
    }
}