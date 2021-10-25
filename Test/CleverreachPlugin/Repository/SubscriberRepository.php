<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\ResourceModel\SubscriberEntity;

class SubscriberRepository
{
    private SubscriberEntity $resourceEntity;

    /**
     * SubscriberRepository constructor.
     */
    public function __construct()
    {
        $this->resourceEntity = ObjectManager::getInstance()->create(SubscriberEntity::class);
    }

    /**
     * Get subscribers from database for current group number.
     *
     * @param int $groupNumber
     *
     * @return array
     */
    public function getSubscribers(int $groupNumber): array
    {
        return $this->resourceEntity->select($groupNumber);
    }

    /**
     * Get number of subscribers in database.
     *
     * @return int
     */
    public function numberOfSubscribers(): int
    {
        return $this->resourceEntity->count();
    }
}