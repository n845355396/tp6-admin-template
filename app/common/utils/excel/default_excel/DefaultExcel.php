<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/31 15:21
 * @Description: Description
 * 
 * @return 

 */

namespace app\common\utils\excel\default_excel;


use app\common\utils\excel\ExcelBase;
use app\common\utils\excel\ExcelInterface;
use Exception;
use PHPExcel_IOFactory;
use think\facade\App;

class DefaultExcel extends ExcelBase implements ExcelInterface
{

    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 15:03
     * @Description: 导入
     * @param $path : 文件路径
     * @return array:返回解析后的数组
     */
    public function import($path, $isUrl = false): array
    {
        try {
            if ($isUrl) {
                $urlFileContent = file_get_contents($path);
                $path           = tempnam("storage", "import_");
                $handle         = fopen($path, "w");
                fwrite($handle, $urlFileContent);
                fclose($handle);
            }

            //获取excel文件
            $objPHPExcel = PHPExcel_IOFactory::load($path);
            $objPHPExcel->setActiveSheetIndex(0);
            $excel_array = $objPHPExcel->getSheet(0)->toArray();
            array_shift($excel_array);  //删除第一个数组(标题);

            if ($isUrl) {
                unlink($path);
            }

            return self::serviceSucc($excel_array, '解析成功');
        } catch (Exception $e) {
            return self::serviceError($e->getMessage());
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
     */
    public function export(string $fileName, array $headArr = [], array $data = [], string $dir = ''): array
    {
        try {
            $fileName = $fileName . '.csv';
            $dir      = empty($dir) ?
                str_replace('//', '/', $this->config['default_dir'])
                : $dir;

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $filePath = str_replace('//', '/', $dir . '/' . $fileName);

            ini_set('memory_limit', '1024M'); //设置程序运行的内存
            ini_set('max_execution_time', 0); //设置程序的执行时间,0为无上限
            ob_end_clean();  //清除内存
            ob_start();
            $fp = fopen($filePath, 'w');
            fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($fp, $this->disposeHead($headArr));
            $index = 0;
            foreach ($data as $item) {
                //每次写入1000条数据清除内存
                if ($index == 1000) {
                    $index = 0;
                    ob_flush();//清除内存
                    flush();
                }
                $index++;
                fputcsv($fp, $item);
            }
            ob_flush();
            flush();
            ob_end_clean();
            fclose($fp);
            $fullFileUrl = getDomainUrl($filePath);
            return self::serviceSucc(['file_path' => $filePath, 'full_file_url' => $fullFileUrl], '导出成功');
        } catch (Exception $e) {
            return self::serviceError($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 15:11
     * @Description: 直接导出文件流到页面
     * @return void
     */
    public function exportOutput(string $fileName, array $headArr = [], array $data = [])
    {
        $fileName = $fileName . '.csv';
        //输出的文件类型为excel
        header("Content-type:application/vnd.ms-excel");
        //提示下载
        header("Content-Disposition:filename=$fileName");

        ini_set('memory_limit', '1024M'); //设置程序运行的内存
        ini_set('max_execution_time', 0); //设置程序的执行时间,0为无上限
        ob_end_clean();  //清除内存
        ob_start();
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, $this->disposeHead($headArr));
        $index = 0;
        foreach ($data as $item) {
            if ($index == 1000) { //每次写入1000条数据清除内存
                $index = 0;
                ob_flush();//清除内存
                flush();
            }
            $index++;
            fputcsv($fp, $item);
        }

        ob_flush();
        flush();
        ob_end_clean();
        exit();
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/31 16:54
     * @Description: 处理heard避免出现ID
     * @param $headArr
     */
    private function disposeHead($headArr)
    {
        if ($headArr[0] == 'ID') {
            $headArr[0] = '编号';
        }
        return $headArr;
    }
}