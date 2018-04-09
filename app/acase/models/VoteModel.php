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
    var $weixin_code;

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'acase');
        $this->table_name = CASE_VOTE_CANDIDATE;
        $this->key_id = 'cvc_id';

        $this->weixin_code = 'alanbeibei';

        $this->model('\captain\system\UserModel', 'userMod', 'system');
        $this->model('\captain\weixin\WeixinModel', 'weixinMod', 'weixin');
        $this->weixinMod->config($this->weixin_code);

        $this->return_status[1] = "用户不存在";
        $this->return_status[2] = "每天只能投一票";
        $this->return_status[3] = "活动还没有开始";
        $this->return_status[4] = "活动已经结束";
        $this->return_status[5] = "您还没有关注微信公众号";
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
     * 投票
     * @param $user_code
     * @param $cvc_id
     * @return Ret
     */
    public function vote($user_code, $cvc_id)
    {
        $ret = new Ret($this->return_status);
        $user = $this->userMod->get_user($user_code);
        if(!$user) {
            $ret->set_code(1);
            return $ret;
        }
        if (!$user['weixin'] || $user['weixin']['subscribe'] == 0) {
            $wx_userinfo = $this->weixinMod->sync_weixin_user_info($this->weixin_code, $user['user_wxopenid']);
            if (!$wx_userinfo || !isset($wx_userinfo['subscribe']) || !$wx_userinfo['subscribe']) {
                $ret->set_code(5);
                return $ret;
            }
        }

        $ballot_count = $this->get_ballot_count($user_code, 'day', date('Ymd'));
        if ($ballot_count >= 1) {
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