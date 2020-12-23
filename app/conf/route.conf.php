<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

return [
    // 'url 可用正则' => ['c' => '控制器', 'm' => '方法'];
    //"portal" => ["c" => \App\control\Portal::class, "m" => "index"],
    "product"      => ["c" => \App\control\PortalControl::class, "m" => "index"],
    "product/save" => ["c" => \App\control\PortalControl::class, "m" => "save"],
];
