<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/31 16:05
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\service;


use app\common\utils\ExcelUtil;
use Exception;

class ExcelService extends BaseService
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 15:03
     * @Description: 导出
     * @param string $fileName :文件名
     * @param array $headArr :表头
     * @param array $data :数据
     * @param string $dir :保存目录
     * @return array :返回下载url
     * @throws Exception
     */
    public function export(string $fileName, array $headArr = [], array $data = [], string $dir = ''): array
    {
        return ExcelUtil::export($fileName, $headArr, $data, $dir);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 15:11
     * @Description: 直接导出文件流到页面
     * @param string $fileName
     * @param array $headArr
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function exportOutput(string $fileName, array $headArr = [], array $data = [])
    {
        ExcelUtil::exportOutput($fileName, $headArr, $data);
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 16:23
     * @Description: 导入数据
     * @param $path
     * @param bool $isUrl:是否是url
     * @return array:返回数组
     * @throws Exception
     */
    public  function import($path, bool $isUrl = false): array
    {
        return ExcelUtil::import($path,$isUrl);
    }
}