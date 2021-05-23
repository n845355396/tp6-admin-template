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

    $url = str_replace('//', '/', $_SERVER['HTTP_HOST'] . '/' . $path);

    return $http_type . $url;
}

/**
 * @Author: lpc
 * @DateTime: 2021/5/19 18:02
 * @Description: 对象转数组
 * @param $object
 * @return mixed
 */
function objectToArray($object)
{
    if (is_array($object)) {
        return $object;
    }
    return json_decode(json_encode($object), true);
}
























