<?php

namespace Test\CleverreachPlugin\Service\Authorization\DTO;

use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class AccessToken extends \Test\CleverreachPlugin\Service\DTO\CleverReachInformation
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct(CleverReachConfig::ACCESS_TOKEN_NAME);
        $this->token = $value;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['value' => $this->token];
    }
}
