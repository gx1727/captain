<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-03-27
 * Time: 上午 7:10
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Controller;

class Tools extends Controller
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');

        $this->return_status[1] = '';
    }

    public function imagetools_proxy()
    {
        $validMimeTypes = array("image/gif", "image/jpeg", "image/png");

        if (!isset($_GET["url"]) || !trim($_GET["url"])) {
            header("HTTP/1.0 500 Url parameter missing or empty.");
            return;
        }

        $scheme = parse_url($_GET["url"], PHP_URL_SCHEME);
        if ($scheme === false || in_array($scheme, array("http", "https")) === false) {
            header("HTTP/1.0 500 Invalid protocol.");
            return;
        }

        $content = file_get_contents($_GET["url"]);
        $info = getimagesizefromstring($content);

        if ($info === false || in_array($info["mime"], $validMimeTypes) === false) {
            header("HTTP/1.0 500 Url doesn't seem to be a valid image.");
            return;
        }

        header('Content-Type:' . $info["mime"]);
        echo $content;
    }
}