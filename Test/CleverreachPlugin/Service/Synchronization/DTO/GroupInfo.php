<?php

namespace Test\CleverreachPlugin\Service\Synchronization\DTO;

use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\DTO\CleverReachInformation;

class GroupInfo extends CleverReachInformation
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
        parent::__construct(CleverReachConfig::GROUP_INFO_NAME);
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
