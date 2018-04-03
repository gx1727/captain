<?php

namespace captain\acase;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 12:56
 */

include_once BASEPATH . 'app/acase/core/CaseController.php';
use \captain\acase\CaseController;

class Acase extends CaseController
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'case');

        $this->return_status[1] = '';
    }

    public function index()
    {
        echo "dds";
    }
}