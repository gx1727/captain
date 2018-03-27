<?php
/**
 * 菜单目录
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/25
 * Time: 14:11
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');


use \captain\core\Model;
use \captain\core\Ret;


class MenuModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_MENU;
        $this->key_id = 'menu_id';

        $this->model('\captain\system\AuthModel', 'authMod');

        $this->library('\captain\core\Sort', 'sortLib');
        $this->sortLib->init($this, CAPTAIN_MENU, 'menu_id', 'menu_parent', 'menu_subindex', 'menu_index', 'menu_title', 'menu_order');
        $this->return_status[1] = '目录不存在';
        $this->return_status[2] = '目录有下级目录，有能删除';
        $this->return_status[3] = '上级目录不能为自己';
    }

    public function get_menu($menu_id)
    {
        return $this->get($menu_id, CAPTAIN_MENU, 'menu_id');
    }

    public function add_menu($menu_title, $menu_pinyin, $menu_href, $menu_icon, $menu_parent, $menu_order)
    {
        $data = array(
            'menu_title' => $menu_title,
            'menu_pinyin' => $menu_pinyin,
            'menu_href' => $menu_href,
            'menu_icon' => $menu_icon,
            'menu_parent' => $menu_parent,
            'menu_order' => $menu_order
        );
        return $this->add($data);
    }

    public function edit_menu($menu_id, $menu_data)
    {
        if ($menu_data['menu_parent'] == $menu_id) {
            $ret = new Ret($this->return_status, 3);
            return $ret;
        } else {
            return $this->edit($menu_id, $menu_data, CAPTAIN_MENU, 'menu_id');
        }
    }

    public function del_menu($menu_id)
    {
        $child = $this->get($menu_id, CAPTAIN_MENU, 'menu_parent');
        if ($child) {
            $ret = new Ret($this->return_status, 2);
            return $ret;
        } else {
            return $this->del($menu_id, CAPTAIN_MENU, 'menu_id');
        }
    }

    /**
     * 获到目录树
     * @param $root_id
     * @return array
     */
    public function get_menu_tree($root_id)
    {
        return $this->sortLib->get_sort_tree($root_id, 'manage_menu_node', 'children');
    }

    public function manage_menu_node($sort_node)
    {
        $sort_node['title'] = $sort_node['menu_title'];
        $sort_node['name'] = $sort_node['menu_href'];
        $sort_node['icon'] = $sort_node['menu_icon'];
        $sort_node['expand'] = true;
        return $sort_node;
    }

}