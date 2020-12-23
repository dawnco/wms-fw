<?php
/**
 * @author Dawnc
 * @date   2020-12-08
 */
return [
    "env"       => "dev",   // 环境 dev 开发  production 生产
    "log_dir"   => "",      // 日志目录
    "log_level" => "error", // 日志登录  debug info  warning error
    "db"        => [
        "default" => [
            "driver"   => \wms\database\Mysqli::class,
            "hostname" => "127.0.0.1",
            "username" => "root",
            "password" => "root",
            "database" => "test",
            "port"     => 3306,
            "charset"  => "utf8mb4",
        ],
    ],

];
