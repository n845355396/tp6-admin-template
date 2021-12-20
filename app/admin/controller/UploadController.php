<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/17 17:27
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\controller;


use app\common\service\Kernel;
use app\common\service\UploadService;
use app\common\utils\ElasticsearchUtil;
use app\common\utils\Result;
use think\facade\Route;
use think\response\Json;

class UploadController extends BaseController
{
    public function testEs()
    {
        set_time_limit(0);
        $esService = new ElasticsearchUtil('goods-test');

//        $body = [
//            'settings' => [
//                'number_of_shards' => 3,
//                'number_of_replicas' => 2
//            ],
//            'mappings' => [
//                '_source' => [
//                    'enabled' => true
//                ],
//                'properties' => [
//                    'goods_name' => [
//                        'type' => 'text',
//                        'index' => true
//                    ],
//                    'price' => [
//                        'type' => 'float'
//                    ],
//                    'is_on_sale' => [
//                        'type' => 'byte'
//                    ],
//                    'tag' => [
//                    ],
//                ]
//            ]
//        ];
//        $result = $esService->createIndex($body);

//        $num = 100000002+400000;
//        for ($iv=$num; $iv < 100000+$num; $iv++) {
//            $b = '';
//            for ($i = 0; $i < rand(5,10); $i++) {
//                // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
//                $a = chr(mt_rand(0xB0, 0xD0)) . chr(mt_rand(0xA1, 0xF0));
//                // 转码
//                $b .= iconv('GB2312', 'UTF-8', $a);
//            }
//
//            $tag = '';
//            for ($i = 0; $i < rand(5,10); $i++) {
//                // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
//                $a = chr(mt_rand(0xB0, 0xD0)) . chr(mt_rand(0xA1, 0xF0));
//                // 转码
//                $tag .= iconv('GB2312', 'UTF-8', $a);
//            }
//
//            $data = [
//                'goods_name' => $b,
//                'price' => (rand(100,9999999))*0.01,
//                'is_on_sale' => rand(0,1),
//                'tag' => str_split($tag,6)
//            ];
//            $result = $esService->createDocument($iv+1, $data);
//        }


       $res =  $esService->getDocument(0);
        echo "<pre>";
        print_r($res);
        exit;
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 17:27
     * @Description: 上传图片
     */
    public function image(): Json
    {
        $files = $this->request->file();
        $res = Kernel::single(UploadService::class)->image($files);
        return Result::disposeServiceRes($res);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/17 17:27
     * @Description: 上传文件
     */
    public function file(): Json
    {
        $files = $this->request->file();
        $res = Kernel::single(UploadService::class)->file($files);
        return Result::disposeServiceRes($res);
    }
}