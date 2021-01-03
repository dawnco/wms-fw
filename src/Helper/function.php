<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

/**
 * @param  $key
 * @param  $default
 * @return mixed
 */
function input($key = null, $default = null)
{
    $global = $_POST ?: ($_GET ?: null);

    if (!$global) {
        $global = json_decode(file_get_contents("php://input"), true);
    }

    if ($key === null) {
        $val = $global ?? $default;
    } else {
        $val = $global[$key] ?? $default;
    }

    if (is_array($val)) {
        foreach ($val as $k => $v) {
            $val[$k] = trim($v);
        }
    } else {
        $val = trim($val);
    }

    return $val;
}

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
