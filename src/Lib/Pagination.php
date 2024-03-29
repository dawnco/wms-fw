<?php

namespace Wumashi\Lib;

use wumashi\core\Registry;

/**
 * 分页类
 * @author  : 五马石 <abke@qq.com>
 * @link    : http://blog.wumashi.com
 * @datetime: 2014-4-17
 * @version : 1.0
 * @Description
 */
class Pagination
{

    private $__total; //总记录数
    private $__totalPage; //总页数
    private $__size; //每页记录数
    private $__currentPage; //当前页码
    private $__page = 0; //指定的当前页
    private $__pageTag = "{page}"; //页面变量模板
    private $__pageVar = "page"; //page 参数变量
    private $__showNum = 10; //显示多少个页码
    private $__pageUrl = null; // 分页url 模板
    private $__firstPageUrl = ""; //第一页地址
    private $__startNum;
    private $__endNum;

    /**
     * @param type $option array("total" => 总记录数, "size"=>每页数目)
     */
    public function __construct($option)
    {

        foreach ($option as $key => $value) {
            $val = "__" . $key;
            $this->$val = $value;
        }

        $this->__getPageUrl();

        $this->__totalPage = ceil(($this->__total ? $this->__total : 1) / $this->__size);

        //最大页数限制
        if (isset($option['maxPage'])) {
            $this->__totalPage = $this->__totalPage <= $option['maxPage'] ? $this->__totalPage : $option['maxPage'];
        }

        if ($this->__page) {
            //指定的页码
            $this->__currentPage = $this->__page;
        } else {
            $this->__currentPage = input($this->__pageVar, "i") ?: 1;
        }

        if ($this->__currentPage > $this->__totalPage) {
            $this->__currentPage = $this->__totalPage;
        }

        $this->__calcPageNum();
    }

    /**
     * 获取分类URL
     */
    private function __getPageUrl()
    {
        if ($this->__pageUrl == null) {
            $uri = Registry::get("request")->getUri();
            $uri = $uri == "protal" ? "" : $uri;
            $get = $_GET;
            unset($get[$this->__pageVar]);
            unset($get["route"]);

            $get['page'] = "{page}";

            $this->__pageUrl = site_url($uri, $get);
        }
    }

    /**
     * 计算偏移量
     */
    private function __calcPageNum()
    {

        //显示几个
        $length = ceil($this->__showNum / 2);

        if ($this->__currentPage <= $length) {
            //前4页
            $this->__startNum = 1; //起始页
            $this->__endNum = $this->__showNum < $this->__totalPage ? $this->__showNum : $this->__totalPage;
        } elseif ($this->__currentPage >= $this->__totalPage - $length) {
            //最有4页
            $this->__endNum = $this->__totalPage;
            $start = $this->__endNum - $this->__showNum + 1;
            $this->__startNum = $start > 1 ? $start : 1; //起始页
        } else {
            $start = $this->__currentPage - $length + 1;
            $end = $start + $this->__showNum - 1;

            if ($start == 0) {
                $start = 1;
                $end = $this->__showNum;
            }

            $this->__startNum = $start; //起始页
            $this->__endNum = $end < $this->__totalPage ? $end : $this->__totalPage;
        }

    }

    /**
     * 生成url
     * @param type $number
     * @return type
     */
    private function __url($number)
    {

        if ($number == 1 && $this->__firstPageUrl) {
            //自定义首页地址
            return $this->__firstPageUrl;
        } else {
            return str_replace($this->__pageTag, $number, $this->__pageUrl);
        }
    }

    /**
     * 产生分页html;
     * @return string
     */
    public function html()
    {
        $str = "";
        $str .= "<span>" . $this->__total . " 条记录 </span>";
        //只有一页
        if ($this->__totalPage == 1) {
            return $str;
        }

        if ($this->__currentPage > 1) {
            $str .= "<a href=\"" . $this->__url(1) . "\">首页</a>";
        }

        for ($i = $this->__startNum; $i <= $this->__endNum; $i++) {
            if ($i == $this->__currentPage) {
                $str .= "<span class=\"active\">" . $i . "</span>";
            } else {
                $str .= "<a href=\"" . $this->__url($i) . "\">" . $i . "</a>";
            }
        }

        if ($this->__currentPage < $this->__totalPage && $this->__totalPage > 1) {
            $str .= "<a href=\"" . $this->__url($this->__currentPage + 1) . "\">下一页</a>";
        }

        if ($this->__currentPage == $this->__totalPage) {
            $str .= "<span class=\"active last\">末页</span>";
        } else {
            $str .= "<a href=\"" . $this->__url($this->__totalPage) . "\">末页</a>";
        }

        return $str;
    }

    /**
     * mysql LIMIT 部分数据
     * @return type
     */
    public function limit()
    {
        return " LIMIT " . (($this->__currentPage - 1) * $this->__size) . "," . $this->__size . " ";
    }

    /**
     * 总页数
     * @return float
     * @author  Dawnc
     */
    public function getTotalPage()
    {
        return $this->__totalPage;
    }

    /**
     * 当前页码
     * @return mixed
     * @author  Dawnc
     */
    public function getPage()
    {
        return $this->__currentPage;
    }

    /**
     * 最后一页
     * @author  Dawnc
     */
    public function ended()
    {
        return $this->__currentPage == $this->__totalPage;
    }

    /**
     * 每页大小
     * @author  Dawnc
     */
    public function getPageSize()
    {
        return $this->__size;
    }

}
