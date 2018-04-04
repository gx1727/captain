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
        parent::__construct(__NAMESPACE__, 'acase');

        $this->model('\captain\acase\VoteModel', 'voteMod');

        $this->return_status[1] = '';
    }

    public function index()
    {
        $this->view('index');
    }

    public function rank()
    {
        $this->view('rank');
    }

    public function rule()
    {
        $this->view('rule');
    }

    /**
     * 候选人
     */
    public function candidate()
    {
        $this->view('candidate');
    }

    /**
     * 投票
     */
    public function vote()
    {
        $cvc_id = $this->get_post('cvc_id', 0);
        $ret = $this->voteMod->vote($this->user_code, $cvc_id);
        $this->json($this->get_result($ret));
    }
}