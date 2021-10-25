<?php

namespace Test\CleverreachPlugin\Service\Synchronization;

use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Repository\CustomerRepository;
use Test\CleverreachPlugin\Repository\SubscriberRepository;
use Test\CleverreachPlugin\Service\DataModel\Receiver;

class SynchronizationService
{
    private CustomerRepository $customerRepository;
    private SubscriberRepository $subscriberRepository;
    private CleverReachRepository $cleverReachRepository;

    /**
     * SynchronizationService constructor.
     */
    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
        $this->subscriberRepository = new SubscriberRepository();
        $this->cleverReachRepository = new CleverReachRepository();
    }

    /**
     * Set receiver group information.
     *
     * @param string $groupInfo
     */
    public function setGroupInfo(string $groupInfo): void
    {
        $this->cleverReachRepository->setGroupInfo($groupInfo);
    }

    /**
     * Get receiver group information.
     *
     * @return array
     */
    public function getGroupInfo(): array
    {
        return $this->cleverReachRepository->getGroupInfo();
    }

    /**
     * Get part of receivers from customers.
     *
     * @param int $groupNumber
     *
     * @return array Mapped customers to CleverReach receivers
     */
    public function getReceiversFromCustomers(int $groupNumber): array
    {
        $customers = $this->customerRepository->getCustomers($groupNumber);

        return $this->mapCustomersToReceivers($customers);
    }

    /**
     * Get part of receivers from subscribers.
     *
     * @param int $groupNumber
     *
     * @return array Mapped subscribers to CleverReach receivers
     */
    public function getReceiversFromSubscribers(int $groupNumber): array
    {
        $customers = $this->subscriberRepository->getSubscribers($groupNumber);

        return $this->mapSubscribersToReceivers($customers);
    }

    /**
     * Get number of receivers, separately customers and subscribers number.
     *
     * @return array
     */
    public function getNumberOfReceivers(): array
    {
        $number['customer'] = $this->customerRepository->numberOfCustomers();
        $number['subscriber'] = $this->subscriberRepository->numberOfSubscribers();

        return $number;
    }

    /**
     * Map customers from database to Receiver data model.
     *
     * @param array $customers
     *
     * @return array
     */
    private function mapCustomersToReceivers(array $customers): array
    {
        $receivers = [];
        foreach ($customers as $customer) {
            $receiver = new Receiver($customer['entity_id'], $customer['email'], $customer['is_active'],
                $customer['firstname'], $customer['lastname']);
            $receivers[] = $receiver;
        }

        return $receivers;
    }

    /**
     * Map subscribers from database to Receiver data model.
     *
     * @param array $subscribers
     *
     * @return array
     */
    private function mapSubscribersToReceivers(array $subscribers): array
    {
        $receivers = [];
        foreach ($subscribers as $subscriber) {
            $receiver = new Receiver($subscriber['subscriber_id'], $subscriber['subscriber_email'], true);
            $receivers[] = $receiver;
        }

        return $receivers;
    }
}