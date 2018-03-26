<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/25
 * Time: 15:05
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Menu extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->model('\captain\system\AuthModel', 'authMod');
        $this->model('\captain\system\MenuModel', 'menuMod');
        $this->return_status[1] = '角色不存在';
        $this->return_status[2] = '菜单不存在';
    }

    /**
     * 通过用户角色，获到角色的菜单树
     */
    public function get_tree()
    {
        $role_id = $this->input->get_post('role_id');
        $role = $this->authMod->get_role($role_id);

        if ($role) {
            if (!$role['role_menu']) { // 目录还不存在
                $menu_id = $this->menuMod->add_menu($role['role_title'], '', '', '', 1, 99999);
                $role['role_menu'] = $menu_id;
                $this->authMod->role_edit($role['role_id'], array('role_menu' => $menu_id));
            }
            $role_menu_tree = $this->menuMod->get_menu_tree($role['role_menu']);
            $this->json($this->get_result(array('menuTree' => $role_menu_tree, 'root' => $this->menuMod->get_menu($role['role_menu']))));
        } else {
            $this->json($this->get_result(false, 1));
        }
    }

    /**
     * 获取菜单详细
     */
    public function get_menu()
    {
        $menu_id = $this->input->get_post('menu_id');
        if ($menu_id) {
            $menu = $this->menuMod->get($menu_id, CAPTAIN_MENU, 'menu_id');
            $this->json($this->get_result($menu));
        } else {
            $this->json($this->get_result(false, 2));
        }
    }

    /**
     * 编辑菜单
     */
    public function form_menu()
    {
        $menu_id = $this->input->get_post('menu_id');
        $menu_title = $this->input->get_post('menu_title');
        $menu_href = $this->input->get_post('menu_href');
        $menu_icon = $this->input->get_post('menu_icon');
        $menu_parent = $this->input->get_post('menu_parent');
        $menu_order = $this->input->get_post('menu_order');
        if ($menu_id) {
            $menu_data = array(
                'menu_title' => $menu_title,
                'menu_href' => $menu_href,
                'menu_icon' => $menu_icon,
                'menu_parent' => $menu_parent,
                'menu_order' => $menu_order
            );
            $ret = $this->menuMod->edit_menu($menu_id, $menu_data);
            $this->json($this->get_result($ret));
        } else {
            $ret = $this->menuMod->add_menu($menu_title, '', $menu_href, $menu_icon, $menu_parent, $menu_order);
            $this->json($this->get_result($ret));
        }
    }

    public function del_menu()
    {
        $menu_id = $this->input->get_post('menu_id');
        $ret = $this->menuMod->del_menu($menu_id);
        $this->json($this->get_result($ret));
    }

}