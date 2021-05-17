<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 18:11
 * @Description: 七牛云
 * 
 * @return 

 */

namespace app\common\utils\upload\qiniu;


use app\common\utils\upload\UploadBase;
use app\common\utils\upload\UploadInterface;
use League\Flysystem\Exception;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiniuUpload extends UploadBase implements UploadInterface
{

    public function upload($files, bool $isImg = false): array
    {
        if (!$files) {
            return [];
        }
        $config = $this->getConfig();
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = $config['qiniu_access_key'];
        $secretKey = $config['qiniu_access_secret'];
        // 要上传的空间
        $bucket = $config['qiniu_bucket'];
        $domain = $config['qiniu_domain'];

        $resFileData = [];
        foreach ($files as $name => $file) {
            // 要上传图片的本地路径
            $filePath = $file->getRealPath();
            $ext      = $file->getOriginalExtension();  //后缀

            // 上传到七牛后保存的文件名
            $key = substr(md5($file->getRealPath()), 0, 5) . date('YmdHis') . rand(0, 9999) . '.' . $ext;
            // 构建鉴权对象
            $auth  = new Auth($accessKey, $secretKey);
            $token = $auth->uploadToken($bucket);
            // 初始化 UploadManager 对象并进行文件的上传
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

            if ($err !== null) {
                throw new Exception($err);
            } else {
                //返回图片的完整URL
                $resFileData[$name] = str_replace("//", "/", $domain . '/' . $ret['key']);
            }
        }
        return $resFileData;
    }
}