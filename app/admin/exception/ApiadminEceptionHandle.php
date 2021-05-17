<?php


namespace app\admin\exception;


use think\exception\DbException;
use think\exception\Handle;
use think\Response;
use Throwable;

class ApiadminEceptionHandle extends Handle
{

    public $httpCode = 500;

    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }


    public function render($request, Throwable $e): Response
    {

        if (method_exists($e, "getStatusCode")) {
            $httpCode = $e->getStatusCode();
        } else {
            $httpCode = $this->httpCode;
        }
        return json(['code' => $httpCode, 'msg' => $e->getMessage()]);
    }

}