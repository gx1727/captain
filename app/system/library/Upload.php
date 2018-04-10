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

    public function create_filename($file) {
        $this->filename = time() . Rand::getRandChar(8)  . '.' . substr($file['type'], 6);
    }

    public function handlePost($file)
    {
        if($file && isset($file['type'])) {
            switch($file['type']) {
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

    public function image($file) {
        $this->create_filename($file);
        $this->class = 1; // 图片
        $this->filepath = 'upload/' . date('Ymd') . '/';
        if(!file_exists( $this->filepath)) {
            mkdir( $this->filepath);
        }
        $this->filepath .= $this->filename;
        if(move_uploaded_file($file['tmp_name'], BASEPATH . $this->filepath)) {
        } else {
            $this->error = 1;
        }
    }
}