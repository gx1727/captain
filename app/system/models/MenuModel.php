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

        $this->library('\captain\core\Sort', 'sortLib', 'system', array($this, CAPTAIN_MENU, 'menu_id', 'menu_parent', 'menu_subindex', 'menu_index', 'menu_title', 'menu_order'));

        $this->return_status[1] = '';
    }

    public function add_menu($menu_title, $menu_pinyin, $menu_href, $menu_icon, $menu_parent, $menu_order)
    {
        $data = array(
            '$menu_title' => $menu_title,
            '$menu_pinyin' => $menu_pinyin,
            '$menu_href' => $menu_href,
            '$menu_icon' => $menu_icon,
            '$menu_parent' => $menu_parent,
            '$menu_order' => $menu_order
        );
        return $this->add($data);
    }

    /**
     * 获到目录树
     * @param $root_id
     */
    public function get_menu_tree($root_id)
    {
        return $this->sortLib->get_sort_tree($root_id);
    }

}