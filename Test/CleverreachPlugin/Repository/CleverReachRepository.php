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

    /**
     * Set access token in database
     *
     * @param string $token
     */
    public function setToken(string $token) : void
    {
        $this->resourceEntity->insert('accessToken', $token);
    }

    /**
     * Get access token from database
     *
     * @return string|null
     */
    public function getToken() : ?string
    {
        return $this->resourceEntity->select('accessToken');
    }

    /**
     * Set information for created group
     *
     * @param string $groupInfo
     */
    public function setGroupInfo(string $groupInfo) : void
    {
        $this->resourceEntity->insert('groupInfo', $groupInfo);
    }

    /**
     * Get information for group
     *
     * @return array
     */
    public function getGroupInfo(): array
    {
        return json_decode($this->resourceEntity->select('groupInfo'), true);
    }
}