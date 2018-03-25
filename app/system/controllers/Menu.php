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
    }

    public function get_by_role()
    {
        $role_id = $this->input->get_post('role_id');
        $role = $this->authMod->get_role($role_id);
        if ($role) {
            if (!$role['role_menu']) { // 目录还不存在
                $menu_id = $this->menuMod->add_menu($role['role_title'], '', '', '', 1, 99999);
                $role['role_menu'] = $menu_id;
                $this->authMod->role_edit($role['role_id'], array('role_menu' => $menu_id));
            }
            $roleMenuTree = $this->menuMod->get_menu_tree($role['role_menu']);
            $this->json($this->get_result($roleMenuTree));
        } else {
            $this->json($this->get_result(false, 1));
        }
    }
}