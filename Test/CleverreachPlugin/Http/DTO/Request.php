<?php

namespace Test\CleverreachPlugin\Http\DTO;

class Request
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $body;

    /**
     * @var array
     */
    private $queryParameters;

    /**
     * Request constructor.
     *
     * @param string $method
     * @param string $path
     * @param array $body
     * @param array $queryParameters
     */
    public function __construct(string $method, string $path, array $body = [], array $queryParameters = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->body = $body;
        $this->queryParameters = $queryParameters;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    /**
     * Makes URL of Request path and query parameters.
     *
     * @return string
     */
    public function makeUrl(): string
    {
        return $this->getPath() . ($this->getQueryParameters() !== [] ? '?' . http_build_query($this->getQueryParameters()) : '');
    }
}