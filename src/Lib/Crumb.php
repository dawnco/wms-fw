<?php

namespace Wumashi\Lib;

/**
 * 面包屑导航
 * @author  Dawnc
 * @date    2015-04-14
 */
class Crumb
{

    private static $__data = array();

    /**
     * 添加导航
     * @param type $name 名称
     * @param type $url  链接
     * @param type $data 数据
     */
    public static function add($name, $url = false, $data = array())
    {
        self::$__data[] = array("name" => $name, "url" => $url, "data" => $data);
    }

    public static function data()
    {
        return self::$__data;
    }

    /**
     * 面包屑 html
     * @param type $local 位置描述
     * @param type $sp    分隔符
     * @return string
     */
    public static function html($local = "您当前位置 : ", $sp = " &gt; ")
    {
        $html = array();


        if (self::$__data) {

            $html[] = sprintf('<a href="%s">%s</a>', site_url(), "首页");

            foreach (self::$__data as $vo) {
                if ($vo['url']) {
                    $html[] = sprintf('<a href="%s">%s</a>', $vo['url'], $vo['name']);
                } else {
                    $html[] = sprintf('<span>%s</span>', $vo['name']);
                }
            }

            $str =
                '<div class="crumb"><span class="crumb-title">' . $local . "</span>" . implode($sp, $html) . "</div>";
        } else {
            $str = "";
        }
        return $str;
    }

}
