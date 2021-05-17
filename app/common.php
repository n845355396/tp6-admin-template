<?php
// 应用公共文件


/**
 * @Author: lpc
 * @DateTime: 2021/5/17 16:53
 * @Description: 获取服务器url
 * @param string $path
 * @return string|string[]
 */
function getDomainUrl(string $path = '')
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $domain    = $http_type . $_SERVER['HTTP_HOST'];

    return str_replace('//', '/', $domain . '/' . $path);
}