<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 17:01
 * @Description: 本地上传
 * 
 * @return 

 */

namespace app\common\utils\upload\local;


use app\common\utils\upload\UploadInterface;
use app\common\utils\upload\UploadBase;
use Error;
use Exception;
use think\facade\Filesystem;

class LocalUpload extends UploadBase implements UploadInterface
{

    public function upload($files, bool $isImg = false): array
    {
        if (!$files) {
            return self::serviceError('请上传文件！');
        }
        try {
            $config   = $this->getConfig();
            $savePath = $isImg ? $config['img_path'] : $config['file_path'];

            // file('文件域的字段名')
//        $file = request()->file('img');
            $res = [];
            foreach ($files as $fileName => $file) {
                // 当前文件存储位置：public/storage/topic/当前时间/文件名
                $saveName       = Filesystem::disk('public')->putFile($savePath, $file);
                $url            = getDomainUrl('/storage/' . $saveName);
                $res[$fileName] = $url;
            }
            // 将上传后的文件位置返回给前端
            return self::serviceSucc($res,'上传成功');
        } catch (Exception | Error $e) {
            return self::serviceError($e->getMessage());
        }
    }
}