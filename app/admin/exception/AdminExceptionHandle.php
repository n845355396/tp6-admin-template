<?php


namespace app\admin\exception;

use Exception;
use think\exception\Handle;
use think\Response;
use Throwable;

class AdminExceptionHandle extends Handle
{

    public $httpCode = 500;

    public function report(Throwable $exception): void
    {
        if (!$this->isIgnoreReport($exception)) {
            // 收集异常数据
            $data = [
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'message' => $this->getMessage($exception),
                'code'    => $this->getCode($exception),
            ];
            $log  = "[{$data['code']}]{$data['message']}[{$data['file']}:{$data['line']}]";


            if ($this->app->config->get('log.record_trace')) {
                $log .= PHP_EOL . $exception->getTraceAsString();
            }

            try {
                $this->app->log->record($log, 'error');
            } catch (Exception $e) {
            }
        }
    }


    public function render($request, Throwable $e): Response
    {
        if (method_exists($e, "getStatusCode")) {
            $httpCode = $e->getStatusCode();
        } else {
            $httpCode = $this->httpCode;
        }
        $data = [
            'code'       => $httpCode,
            'msg'        => $e->getMessage(),
            'error_file' => $e->getFile() . '：' . $e->getLine() . '行',
        ];
        return json($data);
    }

}