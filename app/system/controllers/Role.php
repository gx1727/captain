<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/14
 * Time: 14:57
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Role extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->return_status[1] = '';
    }

    public function alist()
    {
        $page = $this->input->get_post('page', 1); //
        $pagesize = $this->input->get_post('pagesize', PAGE_SIZE); //
        $orderby = $this->input->get_post('orderby'); //
        $ordertype = $this->input->get_post('ordertype', 'desc'); //

        $data = array(
            array(
                'name' => 'Lison',
                'sex' => '<img  height="60" src="https://file.iviewui.com/dist/5429376049d0fefd8a6f70ff67dc327d.jpg"/>男',
                'work' => '前端开发'
            ),
            array(
                'name' => 'lisa',
                'sex' => '女',
                'work' => '程序员鼓励师'
            ),
            array(
                'name' => 'Aleis',
                'sex' => '男',
                'work' => '前端开发'
            ),
            array(
                'name' => 'Aleis',
                'sex' => '男',
                'work' => '前端开发'
            ),
            array(
                'name' => 'Aleis',
                'sex' => '男',
                'work' => '前端开发'
            )
        );
        $this->json($this->get_result(array('data' => $data, 'total' => sizeof($data))));
    }
}