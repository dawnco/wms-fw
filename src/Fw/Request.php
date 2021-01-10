<?php
/**
 * @author Dawnc
 * @date   2020-05-27
 */

namespace Wms\Fw;


class Request
{

    private $rawJson;

    public function __construct()
    {
        $this->rawJson = json_decode(file_get_contents("php://input"), true);
    }

    public function header($name, $default = null)
    {
        $key = "HTTP_" . str_replace("-", "_", strtoupper($name));
        return $_SERVER[$key] ?? $default;
    }

    public function input($key = null, $default = null)
    {
        $val = $this->post($key);
        if ($val !== null) {
            return $val;
        }

        $val = $this->get($key);
        if ($val !== null) {
            return $val;
        }

        $val = $this->data($key);
        if ($val !== null) {
            return $val;
        }

        return $default;

    }

    public function get($key = null, $default = null)
    {
        if ($key == null) {
            return $this->trim($_GET);
        } else {
            return isset($_GET[$key]) ? $this->trim($_GET[$key]) : null;
        }
    }

    public function post($key = null, $default = null)
    {
        if ($key == null) {
            return $this->trim($_POST);
        } else {
            return isset($_POST[$key]) ? $this->trim($_POST[$key]) : $default;
        }
    }

    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? "GET");
    }

    public function data($key = null, $default = null)
    {
        if ($key === null) {
            return $this->rawJson;
        } else {
            return $this->rawJson[$key] ?? $default;
        }
    }

    /**
     * 去掉空格
     * @param $val
     * @return array|string
     */
    protected function trim($val)
    {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                if (is_string($v)) {
                    $val[$k] = trim($v);
                }
            }
        } elseif (is_string($val)) {
            $val = trim($val);
        }
        return $val;
    }
}
