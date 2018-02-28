<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/2/28
 * Time: 14:34
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;

class codeModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        /**
         * 对模块的返回值进行扩展
         */
        $this->return_status[1] = "编码名称不存在";
        $this->return_status[2] = "编码不存在";
        $this->return_status[3] = "编码没有找到";
    }
}