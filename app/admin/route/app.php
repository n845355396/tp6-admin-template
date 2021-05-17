<?php

use think\facade\Route;

Route::rule('/', 'Error/index');

//管理员登录组
Route::group('login', function () {
    Route::post('/login', 'Login/Login');
    Route::get('/get_code_img', 'Login/getCodeImg');
});

//管理员
Route::group('admin', function () {
    Route::post('/create', 'Admin/create');
    Route::get('/list', 'Admin/list');
});

//上传文件
Route::group('upload', function () {
    Route::post('/image', 'Upload/image');
    Route::post('/file', 'Upload/file');
});


Route::miss('Error/noFound');