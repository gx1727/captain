<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * 返回类
 * 作为方法的返回值
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-06
 * Time: 下午 10:32
 */
class Ret
{
    /**
     * 返回状态
     * @var int
     */
    public $code;

    /**
     * Model中定义的状态
     * @var
     */
    public $return_status;

    /**
     * 返回数据的返回集
     * @var
     */
    public $data_1;
    public $data_2;
    public $data_3;
    public $data_4;
    /**
     * 用于返回时的添加值，作用于 get_result 方式
     * @var
     */
    public $result;

    /**
     * 构造函数
     * @param $return_status Model中定义的状态
     */
    public function __construct(&$return_status)
    {
        $this->code = -1;
        $this->return_status = $return_status;

        $this->result = array();
    }

    /**
     * 设置返回的CODE
     * @param $code
     * @param string $msg
     * @param string $format
     */
    public function set_code($code, $format = '', $msg = '')
    {
        if ($format) {
            $this->return_status[$code] = sprintf($format, $this->return_status[$code]);
        }
        if ($msg) {
            $this->return_status[$code] = sprintf($this->return_status[$code], $msg);
        }
        $this->code = $code;
    }

    public function get_code()
    {
        return $this->code;
    }

    /**
     * 设置返回的值
     * @param $data
     * @param int $index
     */
    public function set_data($data, $index = 1)
    {
        $key = "data_" . $index;
        $this->$key = $data;

        $this->set_code(0);
    }

    /**
     * 获取返回的值
     * @param int $index
     * @return mixed
     */
    public function get_data($index = 1)
    {
        $key = "data_" . $index;
        return $this->$key;
    }

    /**
     * result 和 data 容易混淆，区别在于：
     * data：用于MOdel返回给controller ，可能是多个返回值
     * result： 用于controller向上返回的最终值，与 get_result() 配合使用
     * @param $result
     */
    public function set_result($result)
    {
        $this->result = $result;
    }

    /**
     * 增加返回结果
     * 多次调用，结果将合并
     * @param $result 数组
     */
    public function add_result($result)
    {
        $this->result = array_merge($this->result, $result);
    }

    /**
     * 返回结果，自动合并了code 和  msg
     * @return array
     */
    public function get_result()
    {
        return array_merge($this->result, array(
            "code" => $this->code,
            "msg" => $this->status_msg($this->code)));
    }

    /**
     * 通过返回值，获取描述性文字
     * @param $key
     */
    public function status_msg($key)
    {
        if (is_null($key) || is_array($key) || is_object($key)) {
            return $this->return_status[999];
        }
        if (isset($this->return_status[$key])) {
            return $this->return_status[$key];
        } else {
            return $this->return_status[999];
        }
    }
}