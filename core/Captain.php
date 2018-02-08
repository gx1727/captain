<?php defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-02-06
 * Time: 下午 10:44
 */

namespace captain\core;

include_once BASEPATH . 'core/helpers/common.php';
include_once BASEPATH . 'core/libraries/Ret.php';
include_once BASEPATH . 'core/libraries/Route.php';
include_once BASEPATH . 'core/libraries/Controller.php';

require_once BASEPATH . 'app/system/controllers/Index.php';

use \captain\system\Index;

$index = new Index();
$index->index();