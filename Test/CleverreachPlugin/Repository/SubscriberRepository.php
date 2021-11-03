<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class SubscriberRepository
{
    /**
     * @var CollectionFactory
     */
    private $subscriberFactory;

    /**
     * SubscriberRepository constructor.
     */
    public function __construct(
        CollectionFactory $subscriberFactory
    )
    {
        $this->subscriberFactory = $subscriberFactory;
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
        $customerCollection = $this->subscriberFactory->create();
        $customerCollection->setPageSize(CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);
        $customerCollection->setCurPage($groupNumber);

        return $customerCollection->getData();
    }

    /**
     * Get number of subscribers in database.
     *
     * @return int
     */
    public function numberOfSubscribers(): int
    {
        return $this->subscriberFactory->create()->count();
    }
}