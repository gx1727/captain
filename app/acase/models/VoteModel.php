<?php

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/4
 * Time: 16:49
 */
namespace captain\acase;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class VoteModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'acase');
        $this->table_name = CASE_VOTE_CANDIDATE;
        $this->key_id = 'cvc_id';

        $this->model('\captain\system\UserModel', 'userMod', 'system');
        $this->model('\captain\system\LoginModel', 'loginMod', 'system');
        $this->library('\captain\weixin\WeixinLib', 'weixinLib');

        $this->return_status[1] = "";
        $this->return_status[2] = "每天只能投一票";
    }

    public function get_vote_candidate($cvc_id)
    {
        return $this->get($cvc_id);
    }

    public function get_all_vote_candidate($orderby, $ordertype = 'desc')
    {
        $param_array = array();
        $sql = "from " . $this->table_name . ' where cvc_status = 0 ';

        return $this->get_page_list($this->return_status, $sql, $param_array, 1, 999999, $orderby, $ordertype);
    }

    /**
     * 设票
     * @param $user_code
     * @param $cvc_id
     * @return Ret
     */
    public function vote($user_code, $cvc_id)
    {
        $ret = new Ret($this->return_status);
        $ballot_count = $this->get_ballot_count($user_code, 'day', date('Ymd'));
        if($ballot_count >= 1) {
            $ret->set_code(2);
        } else {
            $data = array(
                'cvc_id' => $cvc_id,
                'user_code' => $user_code,
                'cvb_atime' => time(),
                'cvb_year' => date('Y'),
                'cvb_month' => date('Ym'),
                'cvb_week' => date('YW'),
                'cvb_day' => date('Ymd'),
                'cvb_status' => 0
            );
            $this->add($data, CASE_VOTE_BALLOT);
            $ret->set_code(0);
        }

        return $ret;
    }

    public function get_ballot_count($user_code, $key, $val)
    {
        $query_param = array($user_code, $val);
        $sql = 'select count(*) as ballot_count from ' . CASE_VOTE_BALLOT . ' where user_code = ? and cvb_status = 0 ';
        if ($key == 'year') {
            $sql .= ' and cvb_year = ?';
        } else if ($key == 'month') {
            $sql .= ' and cvb_month = ?';
        } else if ($key == 'week') {
            $sql .= ' and cvb_week = ?';
        } else if ($key == 'day') {
            $sql .= ' and cvb_day = ?';
        }
        $vote_ballot = $this->query($sql, $query_param);
        return $vote_ballot['ballot_count'];
    }
}