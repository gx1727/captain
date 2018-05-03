<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 11:04
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;
class Manager extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');

        $this->return_status[1] = '失败';
    }

    public function index()
    {
        $this->view('manager_index');
    }
}