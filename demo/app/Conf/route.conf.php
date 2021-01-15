<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

use App\Control\AdminControl;
use App\Control\CustomerControl;
use App\Control\CustomerFollowControl;
use App\Control\CustomerOrderControl;
use App\Control\DataNameControl;
use App\Control\LoginControl;
use App\Control\PortalControl;
use App\Control\SampleFullControl;

return [
    // 'url 可用正则' => ['c' => '控制器', 'm' => '方法'];
    //"portal" => ["c" => \App\control\Portal::class, "m" => "index"],
    "login"        => ["c" => LoginControl::class, "m" => "index"],
    "logout"       => ["c" => LoginControl::class, "m" => "logout"],
    "product"      => ["c" => PortalControl::class, "m" => "index"],
    "product/save" => ["c" => PortalControl::class, "m" => "save"],
    "data/name"    => ["c" => DataNameControl::class, "m" => "name"],


    "customer"       => ["c" => CustomerControl::class, "m" => "restFull"],
    "customer/(\d+)" => ["c" => CustomerControl::class, "m" => "restFull"],

    "customer/order"       => ["c" => CustomerOrderControl::class, "m" => "restFull"],
    "customer/order/(\d+)" => ["c" => CustomerOrderControl::class, "m" => "restFull"],


    "customer/follow"       => ["c" => CustomerFollowControl::class, "m" => "restFull"],
    "customer/follow/(\d+)" => ["c" => CustomerFollowControl::class, "m" => "restFull"],

    "admin"       => ["c" => AdminControl::class, "m" => 'restFull'],
    "admin/(\d+)" => ["c" => AdminControl::class, "m" => 'restFull'],

    "(\w+)"       => ["c" => SampleFullControl::class, "m" => 'sample'],
    "(\w+)/(\d+)" => ["c" => SampleFullControl::class, "m" => 'sample'],
];
