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
            "hostname" => "8.129.34.119",
            "username" => "backend",
            "password" => "@vLvXFqdUo#rCB#hkHT5IgkA",
            "database" => "loan_market_v2",
            "port"     => 3306,
            "charset"  => "utf8mb4",
        ],
    ],

];