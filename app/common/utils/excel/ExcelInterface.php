<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/31 14:49
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\excel;


interface ExcelInterface
{
    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 15:03
     * @Description: 导入
     * @param $path : 文件路径
     * @return array:返回解析后的数组
     */
    public function import($path): array;

    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 15:03
     * @Description: 导出
     * @param string $dir :保存目录
     * @param $fileName :文件名
     * @param array $headArr :表头
     * @param array $data :数据
     * @return string:返回下载url
     */
    public function export(string $fileName, array $headArr = [], array $data = [], string $dir = ''): array;

    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 15:11
     * @Description: 直接导出文件流到页面
     * @return mixed
     */
    public function exportOutput(string $fileName, array $headArr = [], array $data = []);
}














