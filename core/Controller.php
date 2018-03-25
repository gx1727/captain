<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-07
 * Time: 下午 6:56
 */
class Controller extends Base
{
    var $router;
    var $input;

    var $modular; //模块
    var $namespace; //当前命名空间

    var $view_data; //传给view的数据
    var $return_status; //返回的信息

    function __construct($namespace, $modular)
    {
        parent::__construct($namespace, $modular);

        global $captain_router,
               $captain_input;
        $this->router = &$captain_router;
        $this->input =  &$captain_input;

        $this->modular = $modular;
        $this->namespace = $namespace;

        $this->view_data = array();

        $this->return_status = array();
        $this->return_status[0] = "成功";
        $this->return_status[999] = "无效的返回状态";
    }


    /**
     * 向页面传递参数
     * @param $k
     * @param $v
     */
    protected function assign($k, $v)
    {
        $this->view_data[$k] = $v;
    }

    /**
     * 返回json串
     * @param $data
     * @param bool $cross 如果为真,可跨域
     */
    protected function json($data, $cross = true)
    {
        if ($cross) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header('Access-Control-Allow-Headers: x-requested-with,content-type');
        }
        if (!is_string($data)) {
            $data = json_encode($data);
        }
        header('Content-Type: application/json');
        echo $data;
    }

    /**
     * 调用模板，显示到浏览器
     * @param $view
     * @param bool $buffer
     * @return bool|string
     */
    protected function view($view, $buffer = false, $cross = true)
    {
        if ($cross) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
            header('Access-Control-Allow-Headers: x-requested-with,content-type');
        }

        extract($this->view_data);
        if ($buffer) {
            ob_start();
        }
        include(BASEPATH . 'app/' . $this->modular . '/views/' . $view . '.php');
        if ($buffer) {
            $buffer = ob_get_contents();
            @ob_end_clean();

            return $buffer;
        }
    }

    /**
     * 获到列表所需的几个参数
     * @return array
     */
    public function get_list_param()
    {
        $list_param = array();
        $list_param['page'] = $this->input->get_post('page', 1); //
        $list_param['pagesize'] = $this->input->get_post('pagesize', PAGE_SIZE); //
        $list_param['orderby'] = $this->input->get_post('orderby'); //
        $list_param['ordertype'] = $this->input->get_post('ordertype', 'desc'); //
        return $list_param;
    }

    /**
     * 通过返回值，获取描述性文字
     * 排除非法key导致php报错
     * @param $key
     * @return mixed
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

    /**
     * 获取返回值
     * 使用 Controller 返回时 ： get_result(true, code值)
     * @param $ret
     * @param int $code
     * @param array $param
     * @return array
     */
    public function get_result($ret, $code = 0, $param = array())
    {
        $ret_array = array('code' => $code, 'msg' => $this->status_msg($code));
        foreach ($param as $k => $v) {
            $ret_array['msg'] = str_replace($k, $v, $ret_array['msg']);
        }
        if ($ret instanceof Ret) {
            $ret_array = $ret->get_result();
        } else if (is_object($ret)) {
            $ret_array = get_object_vars($ret);
            $ret_array['code'] = 0;
            $ret_array['msg'] = '';
        } else if (is_array($ret)) {
            $ret_array = array_merge($ret_array, $ret);
        } else if (is_numeric($ret)) {
            $ret_array['id'] = $ret;
        } else if (is_bool($ret) && $code == 0) {//针对   get_result(false)  的处理，直接返回1 失败
            if (!$ret) {
                $ret_array['code'] = 1;
                $ret_array['msg'] = '失败';
            }
        }

        return $ret_array;
    }
}