<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\ResourceModel\CleverReachEntity;
use Test\CleverreachPlugin\Service\DataModel\CleverReachInformation;

class CleverReachRepository
{
    /**
     * @var CleverReachEntity
     */
    protected $resourceEntity;

    /**
     * CleverReachRepository constructor.
     */
    public function __construct()
    {
        $this->resourceEntity = ObjectManager::getInstance()->create(CleverReachEntity::class);
    }

    /**
     * Set information in database.
     *
     * @param CleverReachInformation $information
     */
    public function set(CleverReachInformation $information): void
    {
        $this->resourceEntity->upsert($information);
    }

    /**
     * Get information from database.
     *
     * @param string $name
     *
     * @return CleverReachInformation|null
     */
    public function get(string $name): ?CleverReachInformation
    {
        $resource = $this->resourceEntity->select($name);
        if ($resource === null) {
            return null;
        }

        return new CleverReachInformation($resource['name'], $resource['value']);
    }
}