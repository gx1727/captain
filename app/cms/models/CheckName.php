<?php
/**
 * 验证name的唯一性
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-04-30
 * Time: 下午 3:51
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class CheckName extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->library_core('\captain\core\Rand', 'randLib');
    }

    /**
     * @param $name
     * @param $table
     * @param int $id
     * @return int 查到同名项，返回 false
     *              没有查到同名项，返回 true
     */
    public function check($name, $table = '', $id = 0)
    {
        $table_list = array(
            CMS_SORT => array(
                'table' => CMS_SORT,
                'id' => 'cs_id',
                'name' => 'cs_name',
                'status' => 'cs_status'
            ),
            CMS_TAG => array(
                'table' => CMS_TAG,
                'id' => 'ct_id',
                'name' => 'ct_name',
                'status' => 'ct_status'
            ),
            CMS_SPECIAL => array(
                'table' => CMS_SPECIAL,
                'id' => 's_id',
                'name' => 's_name',
                'status' => 's_status'
            )
        );
        if ($table && isset($table_list[$table])) {
            $query_param = array($name);
            $sql = 'select ' . $table_list[$table]['id'] . ' from ' . $table_list[$table]['table'] . ' where ' . $table_list[$table]['status'] . ' = 0 and ' . $table_list[$table]['name'] . ' = ?';
            if ($id > 0) {
                $sql .= ' and ' . $table_list[$table]['id'] . ' != ? ';
                $query_param[] = $id;
            }
            $sql .= ' limit 1';
            $value = $this->query($sql, $query_param);

            if ($value) {
                return false; // 查到同名项
            } else {
                $table_list[$table] = false;
            }
        }

        $ret = true;
        foreach ($table_list as $node) {
            if ($node) {
                $query_param = array($name);
                $sql = 'select ' . $node['id'] . ' from ' . $node['table'] . ' where ' . $node['status'] . ' = 0 and ' . $node['name'] . ' = ? limit 1';
                $value = $this->query($sql, $query_param);
                if ($value) {
                    $ret = false; // 查到同名项
                    break;
                }
            }
        }
        return $ret;
    }

    /**
     * 获取唯一名称，如有冲突，自动加4位随机串
     * @param $name
     * @return string
     */
    public function get_name($name)
    {
        $name = strtolower($name);  // 名称全部用小写

        while (!$this->check($name)) {
            $name .= $this->randLib->createRand(4, '3456789abcdefghijkmnpqrstuvwxy');
        }
        return $name;
    }
}