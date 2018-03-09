<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 10:35
 *
 * $this->accountMod->refresh_account('rmb');
 * $ret = $this->accountMod->refresh_account('rmb', 1);
 * $ret = $this->accountMod->manage_account('rmb', 'U0000000002', 10, AC_CREBIT_ADJUSTMENT_DECREASE);
 * $ret = $this->accountMod->get_accountloglist('rmb', 1, 1, 99999);
 */

use \captain\core\Model;
use \captain\core\Ret;
use \captain\core\Rand;


class AccountModel extends Model
{
    var $account_name;
    var $account_tablename;
    var $account_log_tablename;
    var $sleep_time;
    var $max_loop;

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_ACCOUNT;
        $this->key_id = 'account_id';

        $this->account_name = ''; // 资金名
        $this->account_tablename = ''; // 表名
        $this->account_log_tablename = ''; // 日志表名

        $this->sleep_time = 500000; //500毫秒一次，
        $this->max_loop = 4;// 重复次ovrn

        $this->return_status[1] = "用户帐户已被删除";
        $this->return_status[2] = "用户帐户已被冻结";
        $this->return_status[3] = "用户帐户已被锁定，暂时不能使用";
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
    public function get_account($account_name, $user_code)
    {
        $ret = new Ret($this->return_status);
        $this->get_tablename($account_name); //  初始化表名

        $account = null;
        $account_ret = $this->lock_account($user_code, 1/*第一次尝试*/, $account);

        if ($account_ret > 0) { // 出错
            $ret->set_result($account);
            $ret->set_data($account);
            $ret->set_code($account_ret);
        } else {
            $this->unlock_account($account['ac_id']);
            $ret->set_result($account);
            $ret->set_data($account);
            $ret->set_code(0);
        }
        return $ret;
    }

    /**
     * @param $account_name 帐户系统名
     * @param $user_code 帐户用户名
     * @param $capital 变更的数量
     * @param $account_type 变更类型
     * @param int $foreign_key 外连KEY
     * @param string $log 日志内容
     * @return Ret
     */
    public function manage_account($account_name, $user_code, $capital, $account_type, $foreign_key = 0, $log = '')
    {
        $ret = new Ret($this->return_status);
        $this->get_tablename($account_name); //  初始化表名

        $account = null;
        $account_ret = $this->lock_account($user_code, 1/*第一次尝试*/, $account);

        if ($account_ret > 0) { // 出错
            $ret->set_code($account);
        } else {
            $account = $this->_manage_account($account, $capital, $account_type, $foreign_key, $log);
            $ret->set_data($account);
            $ret->set_code(0);
            $this->unlock_account($account['ac_id']);
        }
        return $ret;
    }

    /**
     * 获到日志分页列表
     * @param $account_name
     * @param $ac_id
     * @param $page_page
     * @param $page_pagesize
     * @return Ret
     */
    public function get_accountloglist($account_name, $ac_id, $page_page, $page_pagesize)
    {
        //获取表名
        $this->get_tablename($account_name); //  初始化表名

        $param_array = array();
        $sql = "from " . $this->account_log_tablename . "  where ac_id = ? ";
        $param_array[] = $ac_id;

        $start = ($page_page - 1) * $page_pagesize;
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$page_pagesize, 'al_id', 'desc');
    }

    /**
     *  刷新帐户
     *  1.删除原表
     * 2.建立新表
     * @param $account_name
     * @param int $type
     * @return bool
     */
    public function refresh_account($account_name, $type = 1)
    {
        if ($account_name) {
            $sql = 'select * from ' . $this->table_name . ' where account_code = ?  and account_status = 0';
            $account_base = $this->query($sql, array($account_name));

            if ($account_base) {
                // 帐户库已存在 删除
                $this->edit($account_base['account_id'], array('account_etime' => time(), 'account_status' => 1));
                $sql = 'RENAME TABLE `captain`.`xx_account_' . $account_base['account_code'] . '` TO `captain`.`xx_account_' . $account_base['account_code'] . '_' . $account_base['account_id'] . '`';
                $this->query($sql);

                $sql = 'RENAME TABLE `captain`.`xx_account_' . $account_base['account_code'] . '_log` TO `captain`.`xx_account_' . $account_base['account_code'] . '_log_' . $account_base['account_id'] . '`';
                $this->query($sql);

            }
            $this->add(array(
                'account_name' => $account_name,
                'account_code' => $account_name,
                'account_type' => $type,
                'account_atime' => time()
            ));

            $this->get_tablename($account_name);
            if ($type == 1) {
                $this->_manage_account_table('int(11)');
            } else {
                $this->_manage_account_table('decimal(10,2)');
            }
        }
        return true;
    }

    /**
     * 创建新的用户帐户
     * @param $user_code
     * @return array
     */
    private function create_account($user_code)
    {
        $al_data = $this->make_account_log(0, AC_CREATE, 0, 0, 0);

        $data = array(
            'user_code' => $user_code,
            'ac_time' => time(),
            'al_id' => $al_data['al_id'],
            'ac_mark' => $this->make_ac_mark(array("ac_capital" => 0, "user_code" => $user_code, "log" => $al_data)),
        );

        $ac_id = $this->add($data, $this->account_tablename);

        //将帐号ID写回到创建日志中
        $this->edit($al_data['al_id'], array('ac_id' => $ac_id), $this->account_log_tablename, 'al_id');

        //重新获取帐号信息
        $account = $this->_get_account_byusercode($user_code);
        return $account;
    }

    /**
     * 创建积分变更日志
     * @param $ac_id 　帐户ID
     * @param $al_type 　操作类型
     * @param $al_amount 数量
     * @param $foreign_key 关联ID
     * @param $al_capital 当前资金
     * @param string $al_log 日志
     * @return array
     */
    private function make_account_log($ac_id, $al_type, $al_amount, $foreign_key, $al_capital, $al_log = '')
    {
        $data = array(
            'ac_id' => $ac_id,
            'al_amount' => $al_amount,
            'al_type' => $al_type,
            'foreign_key' => $foreign_key,
            'al_mark' => $this->create_mark($al_amount, $al_capital),
            'al_time' => time(),
            'al_capital' => $al_capital,
            'al_log' => $al_log
        );

        $al_id = $this->add($data, $this->account_log_tablename);
        $data['al_id'] = $al_id;
        return $data;
    }

    /**
     * 获到资金数据，带log
     * @param $user_code
     * @return bool
     */
    private function _get_account_byusercode($user_code)
    {
        $account = $this->get($user_code, $this->account_tablename, 'user_code');
        if ($account) {
            $account['log'] = $this->get($account['al_id'], $this->account_log_tablename, 'al_id');
        }
        return $account;
    }

    /**
     * 获取用户帐户锁
     * @param $user_code
     * @param $try_time 尝试的次数
     * @return array|int
     */
    public function lock_account($user_code, $try_time, &$account)
    {
        // 帐户 在锁定状态
        if ($try_time > $this->max_loop) {
            return 3; // 用户帐户已被锁定，暂时不能使用
        }

        $account = $this->_get_account_byusercode($user_code);

        //没有相应的帐户 走新建流程
        if (!$account) {
            $account = $this->create_account($user_code);
        }

        //验证帐户状态
        if ($this->_verification_account($account)) {
            //验证成功，状态正常
            if ($account['ac_flag'] == 1) {
                return 1; // 用户帐户已被删除
            } else if ($account['ac_flag'] == 2) {
                return 2; // 用户帐户已被冻结
            } else {
                // 尝试锁定帐户
                $this->db->where('ac_lock', 0); // 增加一个where条件
                $ret = $this->edit($user_code, array("ac_lock" => 1), $this->account_tablename, 'user_code');
                if ($ret) {
                    //锁定帐户成功
                    return 0;
                } else {
                    usleep($this->sleep_time); //500毫秒一次，重复次
                    return $this->lock_account($user_code, $try_time + 1, $account);
                }
            }
        } else { //验证失败
            $this->frozen_account($account['ac_id'], $this->return_status[4]);
            return 4; // 帐户异常，系统自动冻结该帐户
        }
    }

    /**
     * 冰结积分帐户
     * @param $ac_id
     * @param $afl_info
     */
    public function frozen_account($ac_id, $afl_info)
    {
        $this->edit($ac_id, array("ac_flag" => 2), $this->account_tablename, 'ac_id');
    }

    /**
     * 解锁帐号
     * @param $ac_id
     */
    public function unlock_account($ac_id)
    {
        $this->edit($ac_id, array("ac_lock" => 0), $this->account_tablename, 'ac_id');
    }

    /**
     * 生成验证码
     * @param $account_data
     * @return string
     */
    private function make_ac_mark($account_data)
    {
        //变更标识串 + 变更类型 + 变更金额 +  积分额 +用户串 md5值
        $mark = $account_data['log']['al_mark'] . $account_data['log']['al_type'] . floatval($account_data['log']['al_amount']) . floatval($account_data['ac_capital']) . $account_data['user_code'];

        return md5($mark);
    }

    /**
     * 验证帐户是否有异常
     * @param $account
     * @return bool
     */
    private function _verification_account($account)
    {
        $mark = $this->_get_verification_mark($account);

        if ($account['ac_mark'] == $mark) {
            return true;
        }
        return false;
    }

    /**
     * @param $account
     * @return string
     */
    private function _get_verification_mark($account)
    {
        if ($account['log']['al_type'] >= 100) {
            $account['ac_capital'] += $account['log']['al_amount'];
        } else {
            $account['ac_capital'] -= $account['log']['al_amount'];
        }

        return $this->make_ac_mark($account);
    }

    /**
     * 获取表名
     * 都是小写
     * @param $account_name
     * @return array
     */
    private function get_tablename($account_name = null)
    {
        $old_account = $this->account_name; // 原资源名
        if ($account_name) {
            //获取表名
            $this->account_name = $account_name;
            $this->account_tablename = strtolower(CAPTAIN_ACCOUNT . '_' . $account_name);
            $this->account_log_tablename = $this->account_tablename . '_log';
        }
        $tablename = array(
            'old_account' => $old_account,
            'account_name' => $this->account_name,
            'account' => $this->account_tablename,
            'log' => $this->account_log_tablename
        );
        return $tablename;
    }

    /**
     * @param $account
     * @param $capital 变更的数量
     * @param $account_type
     * @param int $foreign_key 关联ID
     * @return mixed
     */
    private function _manage_account($account, $capital, $account_type, $foreign_key, $log)
    {
        $ac_log_data = $this->make_account_log($account['ac_id'], $account_type, $capital, $foreign_key, $account['ac_capital'], $log);
        $sql = "UPDATE " . $this->account_tablename . " SET `ac_time` = " . time() . ", `al_id` = " . $ac_log_data['al_id'] . ", `ac_capital` = `ac_capital` ";
        if ($account_type < 100) { //入金
            $sql .= "+";
        } else { //出金
            $sql .= "-";
        }
        $sql .= "" . $capital . ", ";
        $account['log'] = $ac_log_data;
        $sql .= "`ac_mark` = '" . $this->make_ac_mark($account) . "' WHERE `ac_id` = " . $account['ac_id'];

        $this->query($sql);

        return $this->_get_account_byusercode($account['user_code']);
    }

    /**
     * 生成随机字符串
     * @param $al_amount 本次修改
     * @param $al_capital 上次总值
     * @param int $length
     * @return null|string
     * 明文： [0]$al_amount长度 + $al_amount + [0]$al_capital
     *     $al_amount长度>10时          $al_capital < 时0
     */
    private function create_mark($al_amount, $al_capital, $length = 24)
    {
        // 上一次的  al_amount
        $sql = 'select * from ' . $this->account_log_tablename . ' order by al_id desc limit 1';
        $last_log = $this->query($sql);
        $last_al_amount = '0';
        if ($last_log) {
            $last_al_amount = (string)$last_log['al_amount'];
        }
        $last_al_amount_length = strlen($last_al_amount);

        // 第一部分
        $code = $last_al_amount_length;
        if ($last_al_amount_length > 10) {
            $code = '0' . $last_al_amount_length;
        }
        $code .= $last_al_amount;

        // 第二部分
        $al_amount = (string)$al_amount; //
        $al_amount_lenght = strlen($al_amount);
        if ($al_amount_lenght <= 10) {
            $code .= (string)$al_amount_lenght;
        } else {
            $code .= '0' . (string)$al_amount_lenght;
        }
        $code .= $al_amount;

        // 第三部分
        $al_capital = (string)$al_capital; //
        $al_capital = str_replace('-', '0', $al_capital); //如果以负数开头，就替换为0
        $code .= $al_capital;

        $rand_mark = Rand::encodeCode($code, $length);

        return $rand_mark;
    }

    /**
     * 处理帐户的数据表
     * @param $amount_type
     */
    private function _manage_account_table($amount_type)
    {
        //创建表
        $sql = "
                CREATE TABLE IF NOT EXISTS `$this->account_tablename` (
                  `ac_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
                  `ac_capital` " . $amount_type . " DEFAULT '0.00' COMMENT '资金额',
                  `ac_time` int(10) DEFAULT NULL COMMENT '最后操作时间',
                  `al_id` int(10) NOT NULL COMMENT '最后修改记录ID',
                  `ac_lock` char(1) DEFAULT '0' COMMENT '互斥锁 0:未锁定 1:已锁定',
                  `ac_mark` varchar(32) NOT NULL COMMENT '记录修改标识',
                  `ac_flag` int(11) DEFAULT '0' COMMENT '0:正常 1:删除 2:冰结',
                  PRIMARY KEY (`ac_id`),
                  KEY `user_code` (`user_code`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户资金表' AUTO_INCREMENT=1 ;
                ";
        $this->query($sql);

        //日志表
        //创建表
        $sql = "
                CREATE TABLE IF NOT EXISTS `$this->account_log_tablename` (
                  `al_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `ac_id` int(10) NOT NULL COMMENT '用户资金帐户ID',
                  `al_amount` " . $amount_type . " DEFAULT '0.00' COMMENT '变更金额',
                  `al_type` int(11) DEFAULT '0' COMMENT '变更类型',
                  `foreign_key` int(11) DEFAULT '0' COMMENT '关联ID',
                  `al_mark` varchar(32) NOT NULL COMMENT '标识串 系统自动生成的6位随机串',
                  `al_time` int(10) DEFAULT NULL COMMENT '操作时间',
                  `al_capital` " . $amount_type . " DEFAULT '0.00' COMMENT '变更当前资金额',
                  `al_log` varchar(256) DEFAULT '' COMMENT '日志内容',
                  PRIMARY KEY (`al_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源变更表' AUTO_INCREMENT=1 ;

                ";
        $this->query($sql);
    }
}