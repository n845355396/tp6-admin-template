<?php

use think\facade\Route;

Route::rule('/', 'Error/index');//默认域名通道
Route::miss('Error/noFound');//所有错误url通道

//@Author: lpc @Description: is_permission=true表示会经过管理员菜单权限验证，反之不填/false表示不验证 @DateTime: 2021/5/18 12:20

//管理员登录组
Route::group('login', function () {
    Route::post('/login', 'Login/Login')
        ->name("管理员登录")->append(['is_permission' => false]);

    Route::get('/get_code_img', 'Login/getCodeImg')
        ->name("获取验证码")->append(['is_permission' => false]);

})->name("登录");

//管理员模块
Route::group('admin', function () {
    Route::get('/login_admin_info', 'Admin/loginAdminInfo')
        ->name("登录管理员信息");

    Route::post('/create', 'Admin/create')
        ->name("管理员创建")->append(['is_permission' => true]);

    Route::post('/edit', 'Admin/edit')
        ->name("管理员编辑")->append(['is_permission' => true]);

    Route::post('/delete', 'Admin/delete')
        ->name("管理员删除")->append(['is_permission' => true]);

    Route::post('/disable', 'Admin/disable')
        ->name("管理员禁用")->append(['is_permission' => true]);

    Route::post('/up_password', 'Admin/upPassword')
        ->name("管理员密码修改")->append(['is_permission' => true]);

    Route::get('/info', 'Admin/info')
        ->name("管理员信息")->append(['is_permission' => true]);

    Route::get('/list', 'Admin/list')
        ->name("管理员列表")->append(['is_permission' => true]);

})->name("管理员管理");

//角色管理
Route::group('role', function () {
    Route::post('/create', 'Role/create')
        ->name("角色创建")->append(['is_permission' => true]);

    Route::post('/edit', 'Role/edit')
        ->name("角色编辑")->append(['is_permission' => true]);

    Route::get('/info', 'Role/info')
        ->name("角色信息")->append(['is_permission' => true]);

    Route::get('/list', 'Role/list')
        ->name("角色列表")->append(['is_permission' => true]);

    Route::post('/delete', 'Role/delete')
        ->name("角色删除")->append(['is_permission' => true]);

    Route::post('/disable', 'Role/disable')
        ->name("角色禁用")->append(['is_permission' => true]);

    Route::get('/permission_list', 'Role/permissionList')
        ->name("权限列表")->append(['is_permission' => false]);
})->name("角色管理");

//文件管理
Route::group('upload', function () {
    Route::post('/image', 'Upload/image')
        ->name("上传图片")->append(['is_permission' => true]);

    Route::post('/file', 'Upload/file')
        ->name("上传文件")->append(['is_permission' => true]);

    Route::post('/test_es', 'Upload/testEs')
        ->name("测试es搜索")->append(['is_permission' => true]);

})->name("文件管理");


//菜单管理
Route::group('menu', function () {
    Route::get('/list', 'Menu/list')
        ->name("菜单列表")->append(['is_permission' => true]);

    Route::get('/info', 'Menu/info')
        ->name("菜单信息")->append(['is_permission' => true]);

    Route::post('/create', 'Menu/create')
        ->name("菜单创建")->append(['is_permission' => true]);

    Route::post('/edit', 'Menu/edit')
        ->name("菜单编辑")->append(['is_permission' => true]);

    Route::post('/hidden', 'Menu/hidden')
        ->name("菜单隐藏/展示")->append(['is_permission' => true]);

    Route::post('/delete', 'Menu/delete')
        ->name("菜单删除")->append(['is_permission' => true]);

})->name("菜单管理");

//队列管理
Route::group('task', function () {
    Route::get('/list', 'Task/list')
        ->name("队列列表")->append(['is_permission' => true]);

})->name("队列管理");

//短信管理
Route::group('sms', function () {
    Route::get('/list', 'Sms/list')
        ->name("短信列表")->append(['is_permission' => true]);

    Route::post('/retry', 'Sms/retry')
        ->name("短信重发")->append(['is_permission' => true]);

})->name("短信管理");


















