<?php
/**
 * @author Dawnc
 * @date   2020-12-24
 */

namespace App\Lib;


use App\exception\NetworkException;

class HttpUtil
{

    private $url            = '';
    private $status         = 0;
    private $ch             = null;
    private $requestHeader  = [];
    private $requestBody    = null;
    private $responseHeader = null;
    private $responseStatus = 0;
    private $responseBody   = null;
    private $error          = null;

    public function __construct($url)
    {
        $this->url = $url;
        $this->ch  = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);

        $this->default();


    }

    private function default()
    {
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0");

        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, 1);
    }

    /**
     * @param array $header Content-type: text/plain   Content-length: 100
     */
    public function setHeader($header = '')
    {
        //         $header = array('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');
        $this->requestHeader[] = $header;
        return $this;
    }

    public function setTimeout($second = 10)
    {
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $second);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $second);
        return $this;
    }


    public function setData($data)
    {
        $this->requestBody = $data;
        return $this;
    }

    /**
     * @param string $method "GET"，"POST"，"CONNECT等
     */
    public function setMethod($method = "GET")
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
        return $this;
    }

    public function request()
    {

        if ($this->requestHeader) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->requestHeader);
        }


        if ($this->requestBody) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->requestBody);
        }

        $body = curl_exec($this->ch);
        $info = curl_getinfo($this->ch);


        $this->error = curl_error($this->ch);

        $header_size  = $info['header_size'];
        $request_size = $info['request_size'];

        $this->requestHeader  = $info['request_header'];
        $this->responseHeader = substr($body, 0, $header_size);
        $this->responseBody   = substr($body, $header_size);

        $httpStatus = $info['http_code'];

        $this->responseStatus = $httpStatus;
        if ($body === false || $this->responseStatus != 200) {
            throw new NetworkException("network error httpStatus: $this->responseStatus: error: $this->error url: $this->url");
        }

        return $this;
    }

    public function getBody()
    {
        return $this->responseBody;
    }

    public function getHeader()
    {
        return $this->responseHeader;
    }

    public function getStatus()
    {
        return $this->responseStatus;
    }

    public function getRequestHeader()
    {
        return $this->requestHeader;
    }

    public function getRequestBody()
    {
        return $this->responseBody;
    }

    public function close()
    {
        curl_close($this->ch);
        $this->ch = null;
    }
}
