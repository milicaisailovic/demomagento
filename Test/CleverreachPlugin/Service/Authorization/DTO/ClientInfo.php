<?php

namespace Test\CleverreachPlugin\Service\Authorization\DTO;

use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class ClientInfo extends \Test\CleverreachPlugin\Service\DTO\CleverReachInformation
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        parent::__construct(CleverReachConfig::CLIENT_INFO_NAME);
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
