<?php
/**
 * @author Dawnc
 * @date   2020-05-27
 */

namespace Wms\Fw;

class Response
{

    protected array $headers = [];
    protected string $body = '';
    protected int $status = 200;

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader($name): array
    {
        return $this->headers[strtolower($name)] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * @param $code
     * @param $reasonPhrase
     * @return Response
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->status = $code;
        return $new;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return Response
     */
    public function withHeader($name, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $normalized = strtolower($name);
        $new = clone $this;
        $new->headers[$normalized] = $value;
        return $new;

    }

    /**
     * @param                  $name
     * @param array|string|int $value
     * @return Response
     */
    public function withAddedHeader($name, $value): Response
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $new = clone $this;
        $normalized = strtolower($name);
        $new->headers[$normalized] = array_merge($new->headers[$normalized], $value);
        return $new;
    }

    public function withoutHeader($name): Response
    {
        $normalized = strtolower($name);
        $new = clone $this;
        unset($new->headers[$normalized]);
        return $new;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Response
     */
    public function withBody(string $body): Response
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

}
