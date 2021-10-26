<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\ResourceModel\CustomerEntity;

class CustomerRepository
{
    private CustomerEntity $resourceEntity;

    /**
     * CustomerRepository constructor.
     */
    public function __construct()
    {
        $this->resourceEntity = ObjectManager::getInstance()->create(CustomerEntity::class);
    }

    /**
     * Get customers from database for current group number.
     *
     * @param int $groupNumber
     *
     * @return array
     */
    public function getCustomers(int $groupNumber): array
    {
        return $this->resourceEntity->select($groupNumber);
    }

    /**
     * Get number of customers in database.
     *
     * @return int
     */
    public function numberOfCustomers(): int
    {
        return $this->resourceEntity->count();
    }
}