<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:54
 *
 * $this->model('\captain\system\UserModel', 'userMod', 'system');
 */
use \captain\core\Controller;
use \captain\core\Rand;

class Index extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->return_status[10] = '不服来战';
    }

    public function index()
    {
        $this->view('index');
    }
}