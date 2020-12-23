<?php
/**
 * @author Dawnc
 * @date   2020-05-27
 */

namespace Wms\Fw;


class Request
{

    public function header($name)
    {
        $key = "HTTP_" . str_replace("-", "_", strtoupper($name));
        return $_SERVER[$key] ?? null;
    }

    public function input($key = null)
    {
        return $this->post($key) ?: $this->get($key);
    }

    public function get($key = null)
    {
        if ($key == null) {
            return $this->trim($_GET);
        } else {
            return isset($_GET[$key]) ? $this->trim($_GET[$key]) : null;
        }
    }

    public function post($key = null)
    {
        if ($key == null) {
            return $this->trim($_POST);
        } else {
            return isset($_POST[$key]) ? $this->trim($_POST[$key]) : null;
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
