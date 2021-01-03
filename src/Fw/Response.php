<?php
/**
 * @author Dawnc
 * @date   2020-05-27
 */

namespace Wms\Fw;


class Response
{

    protected $status = 200;

    protected $headerMap = [
        200 => "HTTP/1.1 200 OK",
        404 => "HTTP/1.1 404 Not Found",
        301 => "HTTP/1.1 301 Moved Permanently",
        500 => "HTTP/1.1 500 Internal Server Error",
    ];


    public function status($status)
    {
        $this->status = $status;
        return $this;
    }

    public function sendJson($code = 0, $msg = null, $data = null)
    {
        $out['code'] = $code;
        if ($msg !== null) {
            $out['msg'] = $msg;
        }
        if ($data !== null) {
            $out['data'] = $data;
        }

        if (Conf::get('app.env') == 'dev') {
            $out['sql'] = Db::instance()->sql;
        }

        $this->send(json_encode($out));
    }

    public function send($str)
    {
        if ($this->status == 200) {
            header('content-type:application/json;charset=utf-8');
        } else {
            $header = $this->headerMap[$this->status] ?? $this->headerMap[500];
            header($header);
        }
        echo $str;
    }
}
