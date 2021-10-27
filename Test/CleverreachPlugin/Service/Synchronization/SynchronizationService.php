<?php

namespace Test\CleverreachPlugin\Service\Synchronization;

use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Repository\CustomerRepository;
use Test\CleverreachPlugin\Repository\SubscriberRepository;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\DataModel\CleverReachInformation;
use Test\CleverreachPlugin\Service\DataModel\Receiver;

class SynchronizationService
{
    private CustomerRepository $customerRepository;
    private SubscriberRepository $subscriberRepository;
    private CleverReachRepository $cleverReachRepository;
    private SynchronizationProxy $synchronizationProxy;

    /**
     * SynchronizationService constructor.
     *
     * @param CleverReachRepository $cleverReachRepository
     * @param SubscriberRepository $subscriberRepository
     * @param CustomerRepository $customerRepository
     * @param SynchronizationProxy $synchronizationProxy
     */
    public function __construct(
        CleverReachRepository $cleverReachRepository,
        SubscriberRepository  $subscriberRepository,
        CustomerRepository    $customerRepository,
        SynchronizationProxy  $synchronizationProxy
    )
    {
        $this->customerRepository = $customerRepository;
        $this->subscriberRepository = $subscriberRepository;
        $this->cleverReachRepository = $cleverReachRepository;
        $this->synchronizationProxy = $synchronizationProxy;
    }

    /**
     * Get client account ID from database.
     *
     * @return int
     */
    public function getClientId(): int
    {
        return (int)json_decode($this->cleverReachRepository->get('clientInfo')->getValue(), true)['id'];
    }

    /**
     * Send request to API for creating new group and set receiver group information in database.
     *
     * @param string $groupName
     */
    public function createGroup(string $groupName): void
    {
        $groupInfoSerialized = $this->synchronizationProxy->createGroup($groupName);
        $data = new CleverReachInformation(CleverReachConfig::GROUP_INFO_NAME, $groupInfoSerialized);
        $this->cleverReachRepository->set($data);
    }

    /**
     * Get receiver group information.
     *
     * @return CleverReachInformation
     */
    public function getGroupInfo(): CleverReachInformation
    {
        return $this->cleverReachRepository->get(CleverReachConfig::GROUP_INFO_NAME);
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
     * Forward receivers to API proxy.
     *
     * @param array $receivers
     *
     * @return array Response
     */
    public function sendReceivers(array $receivers): array
    {
        $groupInfo = $this->getGroupInfo()->getValue();
        $groupId = json_decode($groupInfo, true)['id'];

        return $this->synchronizationProxy->sendReceivers($receivers, $groupId);
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