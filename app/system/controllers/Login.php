<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/3/14
 * Time: 14:57
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Login extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
            return;
        }
        $this->return_status[1] = '';
    }

    public function login_username_pwd()
    {

        $this->model('\captain\system\LoginModel', 'loginMod');

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header('Access-Control-Allow-Headers: x-requested-with,content-type');

        $raw_post_data = file_get_contents('php://input', 'r');
        $post_data = json_decode($raw_post_data, true);

        if (isset($post_data['username']) && isset($post_data['pwd'])) {
            $user = $this->loginMod->username_pwd($post_data['username'], $post_data['pwd']);
            print_r($user);
        }

//        print_r($user);
    }
}