<?php

/**
 * 上传文件
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 15:29
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use captain\core\Rand;

class Upload
{
    var $filename;
    var $name;
    var $class;
    var $type;
    var $size;
    var $filepath;
    var $error;

    public function __construct()
    {
        $this->error = 0;
    }

    public function create_filename($file)
    {
        $file_type = substr($file['type'], 6);
        if ($file_type === 'jpeg') {
            $file_type = 'jpg';
        }
        $this->filename = time() . Rand::getRandChar(8) . '.' . $file_type;
    }

    /**
     * post上传文件
     * @param $file
     * @return array
     */
    public function handlePost($file)
    {
        if ($file && isset($file['type'])) {
            switch ($file['type']) {
                case 'image/gif':
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/png':
                case 'image/svg':
                    $this->image($file);
                    break;
            }
        }

        return array(
            'error' => $this->error,
            'filename' => $this->filename,
            'originalname' => $file['name'],
            'class' => $this->class,
            'type' => $file['type'],
            'size' => $file['size'],
            'filepath' => '/' . $this->filepath,
        );
    }

    public function image($file)
    {
        $this->create_filename($file);
        $this->class = 1; // 图片
        $this->filepath = 'upload/' . date('Ymd') . '/';
        if (!file_exists($this->filepath)) {
            mkdir($this->filepath);
        }
        $this->filepath .= $this->filename;
        if (move_uploaded_file($file['tmp_name'], BASEPATH . $this->filepath)) {
        } else {
            $this->error = 1;
        }
    }

    /**
     * @param $url 网络图片素材
     * @return array
     */
    public function handleNetwork($url)
    {
        $this->filename = time() . Rand::getRandChar(8);
        $this->filepath = 'upload/' . date('Ymd') . '/';
        if (!file_exists($this->filepath)) {
            mkdir($this->filepath);
        }

        $this->download($url);

        $ret = array(
            'error' => 0,
            'class' => 1 // 图片
        );
        $finfo = \finfo_open(FILEINFO_MIME_TYPE);
        $file_type = \finfo_file($finfo, BASEPATH . $this->filepath . $this->filename);
        \finfo_close($finfo);

        $ret['type'] = $file_type;

        $file_type = substr($file_type, 6);
        if ($file_type === 'jpeg') {
            $file_type = 'jpg';
        }

        $ret['filename'] = $this->filename . '.' . $file_type;
        $ret['originalname'] = $ret['filename'];
        $ret['size'] = filesize(BASEPATH . $this->filepath . $this->filename);
        $ret['filepath'] = '/' . $this->filepath . $ret['filename'];

        rename(BASEPATH . $this->filepath . $this->filename, BASEPATH . $this->filepath . $ret['filename']);

        return $ret;
    }

    function download($url)
    {
        $file_url_info = parse_url($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        if ($file_url_info && isset($file_url_info['scheme']) && isset($file_url_info['host'])) {
            curl_setopt($ch, CURLOPT_REFERER, $file_url_info['scheme'] . '://' . $file_url_info['host']);
        }
        $file = curl_exec($ch);
        curl_close($ch);

        $resource = fopen(BASEPATH . $this->filepath . $this->filename, 'w');
        fwrite($resource, $file);
        fclose($resource);
    }
}