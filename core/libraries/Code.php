<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/2/28
 * Time: 12:21
 */

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

class Code extends Base
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->model('\captain\system\CodeModel', 'codeMod', 'system');
    }

    /**
     * 获取编码，对外接口
     * @param $codeName 编码名称
     * @param array $params 参数编码的参数
     * @return string
     */
    public function get_code($codeName, $params = array())
    {
        $code = ''; //最终编码
        $localParam = $this->get_user_params($params); //初始化用户入参

        $code_definition = $this->codeMod->get($codeName);

        if ($code_definition) {
            if ($code_definition['cd_type'] == CD_TYPE_1) { //流水编码
                $this->manage_serial_code($code_definition);
                $attributeset = $code_definition['cd_attributeset'];
                foreach ($attributeset as $node) {
                    $code .= $this->get_code_node($node, $localParam);
                }
            } else if ($code_definition['cd_type'] == CD_TYPE_2) { //检索编码
                $code = $this->manage_retrieval_code($code_definition, $localParam, $code_definition['cd_auto_create_flag']);
            }

        }
        return $code;
    }

    /**
     * 刷新编码
     * 当$codeName 为检索编码时，建建各分表
     * @param $codeName
     */
    public function refresh_code($codeName)
    {
        $code_definition = $this->codeMod->get($codeName);
        if ($code_definition) { //
            if ($code_definition['cd_type'] == CD_TYPE_1) { //流水编码
            } else if ($code_definition['cd_type'] == CD_TYPE_2) { //检索编码
                $this->codeMod->manage_code_table($code_definition); //处理检索编码的数据表
            }
        } else {
            //     print_r($ret->get_result());
        }
    }


    /**
     * 初始化用户入参，整理用户中用户编码的组成部分
     * 流水编码使用
     * @param $params
     * @return array
     */
    private function get_user_params($params)
    {
        $userParams = array(
            "index" => "1", //当前取参下标
            "1" => "",
            "2" => "",
            "3" => "",
            "4" => "",
            "5" => "",
            "6" => "",
            "7" => "",
            "8" => "",
            "9" => "",
            "10" => ""
        );
        if (!is_null($params)) {
            if (is_array($params)) {
                $index = 1;
                foreach ($params as $n) {
                    $userParams[$index++] = $n;
                    if ($index > 10) {
                        break;
                    }
                }
            } else {
                $userParams[1] = $params;
            }
        }

        return $userParams;
    }

    /**
     * 取各个编码项的值
     * 流水编码使用
     * @param $node
     * @param $localParam 引用，便于调整 取参下标index的移动
     * @return string
     */
    private function get_code_node($node, &$localParam)
    {
        $cell = '';
        switch ($node['type']) {
            case "PREFIX": //前缀
            case "SYMBOL": //分隔符
            case "SERIALNUMBER": //流水
            case "SEQUENCE": //递增
            case "HASH": //穷举
                $cell = $node['value'];
                break;
            case "USERNDATA": //用户输入
                if ($node['length']) {
                    $cell = $this->get_serial_number($localParam[$localParam['index']], $node['length']); // 用户输入值受到  length 制约
                } else {
                    $cell = $localParam[$localParam['index']];
                }
                $localParam['index']++;
                break;
            case "TIMES": //时间
                $cell = $this->get_time($node['value'], time());
                break;
            case "RAND":
                $cell = Rand::getRandNumber($node['length']);
                break;
            case "ENCODEID":
                $cell = $this->encodeId($localParam[$localParam['index']], $node['length']);
                $localParam['index']++;
                break;
            default;
                break;
        }

        return $cell;
    }

    /**
     * 要事务完成
     * 处理流水编码
     * @param $code_definition
     */
    private function manage_serial_code(& $code_definition)
    {
        $attributeset = json_decode($code_definition['cd_attributeset'], true);
        $serialNumber = array(); // 自增流水的个数
        foreach ($attributeset as $k => $node) {
            if ($node['type'] == 'SERIALNUMBER') {
                $serialNumber[$k] = $node;
            }
        }
        if (sizeof($serialNumber) > 0) {
            $user_code = "SYS";
            if ($code_definition['cd_level'] != 0) {
                $user_code = $this->user_code;
            }
            $code_info = $this->codeMod->get_code_info($code_definition['cd_name'], $user_code);

            if ($code_info) { //存在流水数据
                $serial_flag = json_decode($code_info['ci_serial_flag'], true);
                $serial_value = json_decode($code_info['ci_serial_value'], true);

                foreach ($serialNumber as $k => $serial) {
                    if ($serial_flag[$k] == "NONE") { //不清零
                        $serial_value[$k]++;
                    } else {
                        $loop = $this->get_time($serial['loop'], false);
                        if ($loop == $serial_flag[$k]) { //在同一个周期内
                            $serial_value[$k]++;
                        } else { //不在同一个周期内，
                            $serial_flag[$k] = $loop;
                            $serial_value[$k] = CODE_START;
                        }
                    }
                    $attributeset[$k]['value'] = $this->get_serial_number($serial_value[$k], $serial['length']);
                }

                $data = array(
                    "ci_serial_flag" => json_encode($serial_flag),
                    "ci_serial_value" => json_encode($serial_value),
                );

                $this->codeMod->update_code_info($code_info['ci_id'], $data);

            } else { //不存在流水数据
                $serial_flag = array();
                $serial_value = array();
                foreach ($serialNumber as $k => $serial) {

                    $loop = $serial['loop'];
                    if ($serial['loop'] != "NONE") { //不清零
                        $loop = $this->get_time($serial['loop'], false);
                    }
                    $serial_flag[$k] = $loop;
                    $serial_value[$k] = CODE_START;

                    $attributeset[$k]['value'] = $this->get_serial_number(CODE_START, $serial['length']);
                }
                $data = array(
                    "cd_name" => $code_definition['cd_name'],
                    "ci_usercode" => $user_code,
                    "ci_serial_flag" => json_encode($serial_flag),
                    "ci_serial_value" => json_encode($serial_value),
                );

                $this->codeMod->insert_code_info($data);
            }
        }
        $code_definition['cd_attributeset'] = $attributeset;
    }


    /**
     * @param $code_definition
     * @param $localParam
     * @param $auto_create_flage 是否自动生成
     * @return string
     */
    private function manage_retrieval_code($code_definition, $localParam, $auto_create_flage)
    {
        $attributeset = json_decode($code_definition['cd_attributeset'], true);

        $code = '';
        $where = array();
        $param = array();
        foreach ($attributeset as $k => $node) {
            if ($node['type'] == "SEQUENCE" || $node['type'] == "HASH") {
                $where[] = $node['name'] . " = ?";
                $param[] = $localParam[$localParam['index']];
                $localParam['index']++;
            }
        }
        $ret = $this->codeMod->get_code_date($code_definition['cd_name'], implode(' AND ', $where), $param);
        if ($ret->get_code() === 0) { //编码已存在

            $codeData = $ret->get_data();
            $code = $codeData['real_code'];
        } else if ($ret->get_code() === 3 && $auto_create_flage) { //编码没有找到，显示$auto_create_flage　生成
            $localParam['index'] = 1; // 参数指针调到第一位

            foreach ($attributeset as $k => $node) {
                if ($node['type'] == "SEQUENCE" || $node['type'] == "HASH") {
                    $ret = $this->codeMod->get_code_node($code_definition['cd_name'], $node['name'], $localParam[$localParam['index']]);
                    $localParam['index']++;
                    if ($ret->get_code() === 0) {
                        $codeNode = $ret->get_data();
                        $attributeset[$k]['value'] = $codeNode['code'];
                    }
                }
            }

            $data = array();

            $code = '';
            $localParam['index'] = 1;
            foreach ($attributeset as $node) {
                $code .= $this->get_code_node($node, $localParam);

                if ($node['type'] == "SEQUENCE" || $node['type'] == "HASH") {
                    $data[$node['name']] = $localParam[$localParam['index']];
                    $localParam['index']++;
                }
            }
            $data["code"] = $code;
            $data["real_code"] = $code;
            $this->codeMod->insert_code_node($code_definition['cd_name'], $data);

        }

        return $code;
    }

    /**
     * 获取补0的流水字符串
     * @param $v
     * @param $len
     * @return string
     */
    function get_serial_number($v, $len)
    {
        $serial = "0000000000000000000000000000000000000000000000000" . $v;
        return substr($serial, -$len);
    }

    /**
     * 获取时间字符串
     * @param $t 时间格式'Y-m-d H:i:s'
     * @param $time
     * @return string
     *
     */
    private function get_time($t, $time)
    {
        if (!$time) {
            $time = time();
        }
        $strTime = '';
        switch ($t) {
            case "YYYYMMDD":
                $strTime = date('Ymd', $time);
                break;
            case "YYMMDD":
                $strTime = date('ymd', $time);
                break;
            case "YYYYMM":
                $strTime = date('Ym', $time);
                break;
            case "YYYY":
                $strTime = date('Y', $time);
                break;
            case "YYYYMMDDHHIISS":
                $strTime = date('YmdHis', $time);
                break;
            case "HHIISS":
                $strTime = date('His', $time);
                break;
            case "TIMESTAMP":
                $strTime = $time;
                break;
            default;
        }
        return $strTime;
    }

    private function delblank($string, $replace = ' ')
    {
        $string = trim($string); //删除首尾空格
        $string = preg_replace('/[sv]+/', $replace, $string); //将多个空字符替换成指定内容
        return $string;
    }

    /**
     * 简单加密数字ID
     * @param $id
     * @param $length
     * @return null|string
     */
    private function encodeId($id, $length)
    {
        $hashtable = array(
            0 => array('0' => '6', '1' => '7', '2' => '1', '3' => '9', '4' => '0', '5' => '5', '6' => '4', '7' => '8', '8' => '3', '9' => '2'),
            1 => array('0' => '9', '1' => '5', '2' => '4', '3' => '2', '4' => '8', '5' => '0', '6' => '1', '7' => '7', '8' => '6', '9' => '3'),
            2 => array('0' => '6', '1' => '4', '2' => '2', '3' => '0', '4' => '8', '5' => '3', '6' => '5', '7' => '9', '8' => '7', '9' => '1'),
            3 => array('0' => '1', '1' => '3', '2' => '9', '3' => '0', '4' => '5', '5' => '4', '6' => '7', '7' => '2', '8' => '8', '9' => '6'),
            4 => array('0' => '1', '1' => '4', '2' => '7', '3' => '2', '4' => '8', '5' => '3', '6' => '9', '7' => '0', '8' => '6', '9' => '5'),
            5 => array('0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9'),
            6 => array('0' => '2', '1' => '8', '2' => '0', '3' => '1', '4' => '7', '5' => '3', '6' => '6', '7' => '9', '8' => '4', '9' => '5'),
            7 => array('0' => '2', '1' => '9', '2' => '7', '3' => '1', '4' => '0', '5' => '3', '6' => '8', '7' => '6', '8' => '4', '9' => '5'),
            8 => array('0' => '7', '1' => '3', '2' => '5', '3' => '2', '4' => '1', '5' => '8', '6' => '4', '7' => '9', '8' => '0', '9' => '6'),
            9 => array('0' => '9', '1' => '5', '2' => '2', '3' => '3', '4' => '8', '5' => '1', '6' => '6', '7' => '0', '8' => '7', '9' => '4'),
        );

        $encode = Rand::getRandNumber($length);
        $str_id = "" . $id;
        $id_length = strlen($str_id);
        if ($id_length > $length - 1) {
            $id_length = $length - 1;
        }
        $hashtable_index = 0;
        if ($id_length < $length - 1) {
            $hashtable_index = $encode[$id_length + 1];
        }

        $encode[0] = $hashtable[0][$id_length];

        for ($i = 0; $i < $id_length; $i++) {
            $encode[$i + 1] = $hashtable[(($i + $hashtable_index) % 10)][$str_id[$i]];
        }


        if ($this->isEven($encode[0]) != $this->isEven($encode[$length - 1])) {
            $encode = strrev($encode);
        }

        return $encode;
    }

    /**
     * 是否为偶数
     * @param $number
     * @return bool
     */
    private function isEven($number)
    {
        return (($number % 2) == 0);
    }
}