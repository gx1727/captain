<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/14
 * Time: 11:44
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

require_once(BASEPATH . "core/third_party/qiniu/autoload.php");

// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

use \captain\core\Controller;
use \captain\core\Rand;

class Test extends Controller
{

    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->model('\captain\cms\CmsModel', 'cmsMod');
        $this->model('\captain\cms\TagModel', 'tagMod');
        $this->model('\captain\cms\RvModel', 'rvMod');
        $this->model('\captain\cms\CheckName', 'checknameMod');
        $this->model('\captain\system\AttachmentModel', 'attMod', 'system');

        $this->return_status[1] = '失败';
    }

    public function index()
    {
        var_dump($this->checknameMod->check('altdt'));
        var_dump($this->checknameMod->check('self-propelled-c'));
    }

    /**
     *
     */
    private function qiniu($att_id)
    {
        $att = $this->attMod->get_attachment($att_id);
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = 'OBJZA8YbD8iCt2psqnGkc7fFpFgmJwYct6r7lizT';
        $secretKey = 'b8V1tYaGVFKOxVWpGXpukQb7JgCYx8LfRLkF3XFC';
        $bucket = '01rv';
        $auth = new Auth($accessKey, $secretKey); // 构建鉴权对象
        $token = $auth->uploadToken($bucket); // 生成上传 Token

        $fileName = $att['att_filepath'];
        $fileName = substr($fileName, 1);

        $filePath = BASEPATH . $fileName; // 要上传文件的本地路径

        $uploadMgr = new UploadManager(); // 初始化 UploadManager 对象并进行文件的上传。

        list($ret, $err) = $uploadMgr->putFile($token, $fileName, $filePath); // 调用 UploadManager 的 putFile 方法进行文件的上传。
        if ($err !== null) {
            $this->log('上传图片到七牛在失败。' . json_decode($err));
        } else {
            $data = array(
                'att_cdn' => 'http://p7bo76bgm.bkt.clouddn.com/' . $fileName
            );
            $this->attMod->edit($att_id, $data);
            @unlink($filePath);
        }
    }
}