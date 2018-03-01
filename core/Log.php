<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 9:11
 */
class Log
{
    const EMERG = 0;
    const FATAL = 0;
    const ALERT = 100;
    const CRIT = 200;
    const ERROR = 300;
    const WARN = 400;
    const NOTICE = 500;
    const INFO = 600;
    const DEBUG = 700;

    private $sys_path; //系统目录
    private $log_path; //日志目录
    private $log_file_name; //日志文件
    private $log_file_path; //日志全路径 $sys_path/$log_path/$log_file_name
    private $level; //日志等级，大于$level的日志直接抛弃

    /**
     *
     *
     * @param $level
     * @param string $log_path
     * @param string $sys_path
     */
    /**
     * gxlog constructor.
     * @param string $sys_path 系统目录
     * @param string $log_path 日志目录
     * @param string $log_file_name 日志文件名
     * @param int $level 日志等级
     */
    function __construct($sys_path = '', $log_path = 'logs', $log_file_name = '', $level = Log::INFO)
    {
        $this->log_file_name = $log_file_name; //如果文件名为空，要自行生成
        $this->log_path = $log_path;
        $this->sys_path = $sys_path ? $sys_path : getcwd(); //默认为调用者访问目录
        $this->level = $level;

        if ($this->init_dir($this->sys_path . DIRECTORY_SEPARATOR . $this->log_path)) {
            $this->set_log_filename();//
            $this->log_file_path = $this->sys_path . DIRECTORY_SEPARATOR . $this->log_path . DIRECTORY_SEPARATOR . $this->log_file_name;
        }
    }

    /**
     * 日志接口
     * 只有一个参数时： 在默认日志文件写入该信息
     * 有多个参数:
     *      第一个参数：日志文件前缀, 可以传 ''
     *      第二个参数：日志信息format
     *        三...N个参数
     */
    public function log()
    {
        $info = '';
        $args_num = func_num_args();
        $args = func_get_args();
        if ($args_num == 1) {
            //只有一个参数，默认文件名
            $this->set_log_filename();//


            $info = $args[0];
        } else if ($args_num > 1) {
            //有两个 或两个以上参数，第一个参数为日志文件前缀
            $prefix = array_shift($args);
            $this->set_log_filename($prefix);//
            $info_format = array_shift($args);
            $info = vsprintf($info_format, $args);
        }
        $this->log_file_path = $this->sys_path . DIRECTORY_SEPARATOR . $this->log_path . DIRECTORY_SEPARATOR . $this->log_file_name;
        $this->write_log($info);
    }

    /**
     * 开日志文件
     * @param $info
     */
    protected function write_log($info)
    {
        $handle = @fopen($this->log_file_path, "a+");
        if ($handle === false) {
            return;
        }
        $date_str = '[' . date('Y-m-d H:i:s') . '] ';

        if (!@fwrite($handle, $date_str . $info . "\r\n")) {
            //echo("写入日志失败");
        }
        @fclose($handle);
    }

    /**
     * 设置日志文件名，根据日期
     * @param string $prefix 日志文件前缀
     */
    protected function set_log_filename($prefix = '')
    {
        if ($prefix) {
            $prefix = $prefix . '_';
        }
        $this->log_file_name = $prefix . date('Ymd') . '.log';
    }

    protected function init_dir($path)
    {
        if (is_dir($path) === false) {
            if (!$this->createDir($path)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 创建目录
     * @param $path
     */
    protected function createDir($path)
    {
        mkdir($path);
    }
}