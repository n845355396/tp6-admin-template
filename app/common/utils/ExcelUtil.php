<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/31 15:49
 * @Description: 导入导出工具类
 * 
 * @return 

 */

namespace app\common\utils;


use Error;
use Exception;
use LogicException;
use think\facade\Config;

class ExcelUtil
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/18 9:46
     * @Description: 获取导入导出对象
     * @return mixed
     * @throws Exception
     */
    public static function getExcelObj()
    {
        try {
            $excelConf = Config::get("excel");
            $type      = $excelConf['type'];
            $config    = Config::get("excel.stores.{$type}");
            $className = $config['class_name'];
            return new $className();

        } catch (Error $e) {
            throw new Exception($e->getMessage());
        }
    }

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
    public static function export(string $fileName, array $headArr = [], array $data = [], string $dir = ''): array
    {
        $obj = self::getExcelObj();
        return $obj->export($fileName, $headArr, $data, $dir);
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
    public static function exportOutput(string $fileName, array $headArr = [], array $data = [])
    {
        $obj = self::getExcelObj();
        $obj->exportOutput($fileName, $headArr, $data);
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
    public static function import($path, bool $isUrl = false): array
    {
        $obj = self::getExcelObj();
        return $obj->import($path,$isUrl);
    }

}








