<?php

namespace captain\core;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 9:54
 */
class Model
{
    var $db;//数据库
    var $modular;

    function __construct()
    {
        global $captain_db;
        $this->db = &$captain_db;
    }
    /**
     * 加载模型
     */
    public function &model($model_name, $modular = null)
    {

    }

    public function &library($library_name, $modular = null)
    {

    }

    public function &help($help_name, $modular = null)
    {

    }

    /**
     * 数据库连接
     */
    public function database()
    {
        if (!$this->db) {
            //建立连接
            require_once(BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'third_party' . DIRECTORY_SEPARATOR . 'MysqliDb.php');
            $db_config = sys_config('db');
            $this->db = new \MysqliDb ($db_config['mysql_host'], $db_config['mysql_username'], $db_config['mysql_pwd'], $db_config['mysql_database']);
        }
    }
}