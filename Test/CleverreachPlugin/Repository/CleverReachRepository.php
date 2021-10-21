<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\ResourceModel\CleverReachEntity;

class CleverReachRepository
{
    protected CleverReachEntity $resourceEntity;

    public function __construct()
    {
        $this->resourceEntity = ObjectManager::getInstance()->create(CleverReachEntity::class);
    }

    public function setToken(string $clientId, string $token) : void
    {
        $this->resourceEntity->setToken($clientId, $token);
    }

    public function getToken(string $clientId) : ?string
    {
        return $this->resourceEntity->getToken($clientId);
    }
}