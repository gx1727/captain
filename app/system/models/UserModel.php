<?php

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-10
 * Time: 上午 10:10
 */

use \captain\core\Model;

class UserModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function test()
    {
        $this->database();
    }
}