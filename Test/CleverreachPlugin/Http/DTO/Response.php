<?php

namespace Test\CleverreachPlugin\Http\DTO;

class Response
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $body;

    /**
     * @param int $status
     * @param string $body
     */
    public function __construct(int $status, string $body)
    {
        $this->status = $status;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Returns json decoded response body.
     *
     * @return array
     */
    public function decodeBodyToArray(): array
    {
        $result = json_decode($this->body, true);

        return !empty($result) ? $result : array();
    }
}