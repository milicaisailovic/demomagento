<?php

namespace Test\CleverreachPlugin\Service\Synchronization;

use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Repository\CustomerRepository;
use Test\CleverreachPlugin\Repository\SubscriberRepository;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\DataModel\CleverReachInformation;
use Test\CleverreachPlugin\Service\DataModel\Receiver;
use Test\CleverreachPlugin\Service\Exceptions\SynchronizationException;

class SynchronizationService
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    /**
     * @var CleverReachRepository
     */
    private $cleverReachRepository;

    /**
     * @var SynchronizationProxy
     */
    private $synchronizationProxy;

    /**
     * @var int
     */
    private $groupId;

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
        $this->groupId = json_decode($this->cleverReachRepository->get('groupInfo')->getValue(), true)['id'];
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
     * Get edited customer from database and send it to API.
     *
     * @param string $email
     *
     * @return void
     */
    public function sendEditedInformation(string $email): void
    {
        $customer = $this->customerRepository->getCustomerByEmail($email);
        $receiver = new Receiver($customer['entity_id'], $customer['email'], strtotime($customer['created_at']),
            strtotime($customer['created_at']), $customer['firstname'], $customer['lastname']);
        $this->sendReceivers([$receiver]);
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
        return $this->synchronizationProxy->sendReceivers($receivers, $this->groupId);
    }

    /**
     * Delete receiver from API group.
     *
     * @param string $email
     */
    public function deleteReceiver(string $email): void
    {
        $this->synchronizationProxy->deleteReceiver($this->groupId, $email);
    }

    /**
     * Get receivers from database and send them to API.
     *
     * @param int $numberOfGroups
     * @param string $type
     *
     * @throws SynchronizationException
     */
    public function synchronizeReceivers(int $numberOfGroups, string $type): void
    {
        for ($i = 1; $i <= $numberOfGroups; $i++) {
            $receivers = ($type === 'customer') ? $this->getReceiversFromCustomers($i) :
                $this->getReceiversFromSubscribers($i);
            $response = $this->sendReceivers($receivers);
            if (isset($response['error'])) {
                throw new SynchronizationException($response[''], $response['status']);
            }
        }
    }

    /**
     * Delete all emails in API group.
     */
    public function truncateGroup()
    {
        $this->synchronizationProxy->truncateGroup($this->groupId);
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
            $receiver = new Receiver($customer['entity_id'], $customer['email'], strtotime($customer['created_at']),
                strtotime($customer['created_at']), $customer['firstname'], $customer['lastname']);
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
            $receiver = new Receiver($subscriber['subscriber_id'], $subscriber['subscriber_email'],
                strtotime($subscriber['change_status_at']), 0);
            $receivers[] = $receiver;
        }

        return $receivers;
    }
}