<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

/**
 * 获取客户端ip
 * @return type
 */
function get_client_ip()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            /* 取X-Forwarded-For中第?个非unknown的有效IP字符? */
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $realip);
    return !empty($realip[0]) ? $realip[0] : '0.0.0.0';
}


/**
 * 获取数组中某个值可以点取值
 * @param      $arr
 * @param      $index
 * @param null $default
 * @return mixed|null
 */
function arr_get($arr, $index, $default = null)
{

    if (is_int($index)) {
        return $arr[$index] ?? $default;
    }

    $ia = explode('.', $index);
    $_t = $arr;
    foreach ($ia as $v) {
        if (isset($_t[$v])) {
            $_t = $_t[$v];
        } else {
            return $default;
        }
    }
    return $_t;
}

function dump(...$args)
{

    $args = func_get_args();

    //if (IS_CLI) {
    //    echo " \033[32;40m";
    //}
    foreach ($args as $v) {
        var_dump($v);
    }
    //if (IS_CLI) {
    //    echo "\033[0m ";
    //}
    exit;
}

function shell_log($msg, ...$param)
{
    echo date("Y-m-d H:i:s ");
    echo vsprintf($msg, $param);
    echo "\n";
}