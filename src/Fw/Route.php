<?php
/**
 * @author Dawnc
 * @date   2020-05-08
 */

namespace Wms\Fw;


class Route
{
    private $uri;
    private $control;
    private $method;
    private $param;

    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->param;
    }


    public function __construct()
    {
        return $this->parse($this->parseUri());
    }


    public function parse($uri)
    {
        $rules = Conf::get("route") ?? [];
        //是否配置过路由
        foreach ($rules as $u => $r) {
            $matches = array();
            if (preg_match("#^$u$#", $uri, $matches)) {
                $params = $this->param($r, $matches);
                $this->control = $r['c'];
                $this->param = $params;
                return null;
            }
        }
        throw new WmsException("no route match : $uri", 404);
    }

    private function parseUri()
    {

        if (isset($_GET['route'])) {
            $uri = $_GET['route'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $pos = strpos($_SERVER['REQUEST_URI'], "?");
            $uri = $pos > 0 ? substr($_SERVER['REQUEST_URI'], 0, $pos) : $_SERVER['REQUEST_URI'];
        } else {
            $uri = "";
        }

        $uri = trim($uri, "/");

        //去掉前缀
        $base_uri = trim(Conf::get("app.base_uri", ""), " /");
        if ($base_uri) {
            if (strpos($uri, $base_uri) === 0) {
                $uri = substr($uri, strlen($base_uri));
            }
        }

        $uri = trim($uri, "/");

        //默认路由
        if (!$uri) {
            $uri = "portal";
        }

        $this->uri = $uri;
        return $uri;
    }

    private function param($rule, $matches = [])
    {

        $this->control = $rule['c'];
        $this->method = isset($rule['m']) ? $rule['m'] : 'index';

        $url_param = array_slice($matches, 1);
        //合并参数
        $param = [];
        if (isset($rule['p'])) {
            $param = array_merge((array)$rule['p'], $url_param);
        } else {
            $param = $url_param;
        }
        $this->param = $param;
        return $param;
    }

    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }
}
