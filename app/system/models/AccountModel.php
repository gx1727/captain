<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 10:35
 */

use \captain\core\Model;
use \captain\core\Ret;


class AccountModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_ACCOUNT;
        $this->key_id = 'ac_id ';

        $this->return_status[1] = "用户积分帐户已被删除";
        $this->return_status[2] = "用户积分帐户已被冻结";
        $this->return_status[3] = "用户积分帐户已被锁定，暂时不能使用";
        $this->return_status[4] = "帐户异常，系统自动冻结该帐户";
        $this->return_status[5] = "帐户不存在";
        $this->return_status[6] = "您的帐户余款不足,不能借钱给别人";
        $this->return_status[7] = "没有找到相应的支付记录";
        $this->return_status[8] = "没有找到相应的取现记录";
        $this->return_status[9] = "新建充值订单失败";
        $this->return_status[10] = "充值订单不存在";
    }

    /**
     * 通过用户编码获取帐户
     * @param $account_name
     * @param $user_code
     * @return Ret
     */
    public function get_account_byusercode($account_name, $user_code)
    {
        $ret = new Ret($this->return_status);
        $account = $this->lock_account($account_name, $user_code, 1/*第一次尝试*/);
        if (is_int($account)) { //出错
            $ret->set_code($account);
            $account_error = $this->_get_account_byusercode($account_name, $user_code);
            $ret->set_result($account_error);
            $ret->set_data($account_error);
        } else {
            $this->unlock_account($account_name, $account['ac_id']);
            $ret->set_code(0);
            $ret->set_result($account);
            $ret->set_data($account);
        }
        return $ret;
    }

    private function _get_account_byusercode($account_name, $user_code)
    {
        //获取表名
        $account_tablename = strtolower(XS_ACCOUNT . '_' . $account_name);
        $account_log_tablename = $account_tablename . '_log';

        $row = false;
        $sql = "SELECT * FROM " . $account_tablename . ' a , ' . $account_log_tablename . " al WHERE a.al_id = al.al_id and a.user_code = ? ";
        $query = $this->db->query($sql, array($user_code));

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }

    /**
     * 获取用户帐户锁
     * * @param $account_name
     * @param $user_code
     * @param $try_time 尝试的次数
     * @return array|int
     */
    public function lock_account($account_name, $user_code, $try_time)
    {
        //获取表名
        $account_tablename = strtolower(XS_ACCOUNT . '_' . $account_name);
        $account_log_tablename = $account_tablename . '_log';

        $sql = "SELECT * FROM " . $account_tablename . ' a , ' . $account_log_tablename . " al WHERE a.al_id = al.al_id and a.user_code = ?";
        $query = $this->db->query($sql, array($user_code));

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        } else { //没有相应的帐户 走新建流程
            $row = $this->create_account($account_name, $user_code);
        }

        if ($this->_verification_account($row)) {
            if ($row['ac_flag'] == 0) {
                return 1;
            } else if ($row['ac_flag'] == 2) {
                return 2;
            } else if ($row['ac_lock'] == 1) {

                if ($try_time > 4) {
                    return 3;
                } else {
                    usleep(500000); //500毫秒一次，重复次
                    return $this->lock_account($account_name, $user_code, $try_time + 1);
                }
            }
            $this->edit($row['ac_id'], array("ac_lock" => 1), $account_tablename, 'ac_id');
            return $row;

        } else { //帐户异常，冻结
            $this->frozen_account($account_name, $row['ac_id'], $this->return_status[4]);
            return 4;
        }
    }

    /**
     * 解锁帐号
     * @param $ac_id
     */
    public function unlock_account($account_name, $ac_id)
    {
        //获取表名
        $account_tablename = strtolower(XS_ACCOUNT . '_' . $account_name);
        $account_log_tablename = $account_tablename . '_log';

        $this->edit($ac_id, array("ac_lock" => 0), $account_tablename, 'ac_id');
    }
}