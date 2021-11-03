<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Test\CleverreachPlugin\ResourceModel\CleverReachEntity;
use Test\CleverreachPlugin\Service\Authorization\DTO\CleverReachInformation;

class CleverReachRepository
{
    const TABLE_NAME = 'cleverreach_entity';
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
        $this->resourceEntity->setTableName(self::TABLE_NAME);
    }

    /**
     * Set information in database.
     *
     * @param CleverReachInformation $information
     */
    public function set(CleverReachInformation $information): void
    {
        try {
            $this->resourceEntity->upsert($information);
        } catch (LocalizedException $exception) {
            return;
        }
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
        try {
            $resource = $this->resourceEntity->select($name);
            if ($resource === null) {
                return null;
            }
            return new CleverReachInformation($resource['name'], $resource['value']);
        } catch (LocalizedException $exception) {
            return null;
        }
    }
}