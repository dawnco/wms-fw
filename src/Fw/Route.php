<?php
/**
 * @author Dawnc
 * @date   2020-05-08
 */

namespace Wms\Fw;


use Wms\Constant\ErrorCode;

class Route
{
    private string $uri;
    private string $control;
    private string $method;
    private array $param;

    /**
     * @return array
     */
    public function getParam(): array
    {
        return $this->param;
    }


    public function __construct()
    {
        return $this->parse($this->parseUri());
    }


    /**
     * @throws WmsException
     */
    public function parse($uri): void
    {
        $rules = Conf::get("route") ?? [];
        //是否配置过路由
        foreach ($rules as $u => $r) {
            $matches = array();
            if (preg_match("#^$u$#", $uri, $matches)) {
                $this->param($r, $matches);
                return;
            }
        }
        throw new WmsException("no route match : $uri", ErrorCode::PAGE_NOT_FOUND);
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

    private function param($rule, $matches = []): void
    {

        $this->control = $rule[0] ?? '';
        $this->method = $rule[1] ?? 'index';

        $url_param = array_slice($matches, 1);
        //合并参数
        $param = [];
        if (isset($rule[2])) {
            $param = array_merge((array)$rule[2], $url_param);
        } else {
            $param = $url_param;
        }
        $this->param = $param;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getControl(): string
    {
        return $this->control;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
