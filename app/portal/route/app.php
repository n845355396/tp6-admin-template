<?php

use think\facade\Route;

Route::rule('/', 'Error/index');//默认域名通道
Route::miss('Error/noFound');//所有错误url通道

//登录
Route::group('login', function () {
    //用户登录
    Route::post('/login', 'Login/login');

});

//用户
Route::group('user', function () {
    //用户登录
    Route::get('/info', 'User/info');
});

//支付
Route::group('payment', function () {
    //app端发起支付
    Route::post('/app_pay', 'Payment/appPay');

    //app端支付异步回调
    Route::post('/notify/:payType', 'Payment/notify');

});

