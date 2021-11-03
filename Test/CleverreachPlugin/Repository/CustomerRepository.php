<?php

namespace Test\CleverreachPlugin\Repository;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class CustomerRepository
{
    /**
     * @var CollectionFactory
     */
    private $customerFactory;

    /**
     * CustomerRepository constructor.
     */
    public function __construct(
        CollectionFactory $customerFactory
    )
    {
        $this->customerFactory = $customerFactory;
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
        $customerCollection = $this->customerFactory->create();
        $customerCollection->setPageSize(CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);
        $customerCollection->setCurPage($groupNumber);

        return $customerCollection->getData();
    }

    /**
     * Get number of customers in database.
     *
     * @return int
     */
    public function numberOfCustomers(): int
    {
        return $this->customerFactory->create()->count();
    }

    /**
     * Get customer from database by email.
     *
     * @param string $email
     *
     * @return array
     */
    public function getCustomerByEmail(string $email): array
    {
        $customerCollection = $this->customerFactory->create();
        $customerCollection->addFieldToFilter('email', ['eq' => $email]);

        return $customerCollection->getData();
    }
}