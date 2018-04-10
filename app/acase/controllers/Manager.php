<?php
namespace captain\acase;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-04-04
 * Time: 下午 9:56
 */

use \captain\core\Controller;


class Manager extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'acase');
        $this->model('\captain\acase\VoteModel', 'voteMod');
        $this->return_status[1] = '';
    }

    public function home()
    {
        $this->view('manager/home');
    }
}