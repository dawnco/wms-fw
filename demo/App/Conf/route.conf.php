<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

return [
    // 'url 可用正则' => ['c' => '控制器', 'm' => '方法'];
    //"portal" => ["c" => \App\control\Portal::class, "m" => "index"],
    "login"        => ["c" => \App\Control\LoginControl::class, "m" => "index"],
    "logout"       => ["c" => \App\Control\LoginControl::class, "m" => "logout"],
    "product"      => ["c" => \App\Control\PortalControl::class, "m" => "index"],
    "product/save" => ["c" => \App\Control\PortalControl::class, "m" => "save"],


    "customer/follow"      => ["c" => \App\Control\CustomerControl::class, "m" => "follow"],
    "customer/follow/list" => ["c" => \App\Control\CustomerControl::class, "m" => "list"],

    "admin"       => ["c" => \App\Control\AdminControl::class, "m" => 'enter'],
    "admin/(\d+)" => ["c" => \App\Control\AdminControl::class, "m" => 'enter'],

    "(\w+)"       => ["c" => \App\Control\TableControl::class],
    "(\w+)/(\d+)" => ["c" => \App\Control\TableControl::class],
];
