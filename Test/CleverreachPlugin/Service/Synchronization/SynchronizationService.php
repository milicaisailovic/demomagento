<?php

namespace Test\CleverreachPlugin\Service\Synchronization;

use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Repository\CustomerRepository;
use Test\CleverreachPlugin\Repository\SubscriberRepository;
use Test\CleverreachPlugin\Service\Authorization\DTO\ClientInfo;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\Synchronization\Contracts\SynchronizationServiceInterface;
use Test\CleverreachPlugin\Service\Synchronization\DTO\GroupInfo;
use Test\CleverreachPlugin\Service\Synchronization\DTO\Receiver;
use Test\CleverreachPlugin\Service\Synchronization\Exceptions\SynchronizationException;
use Test\CleverreachPlugin\Service\Synchronization\Http\SynchronizationProxy;

class SynchronizationService implements SynchronizationServiceInterface
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
     * @var GroupInfo
     */
    private $groupInfo;

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
        $this->groupInfo = new GroupInfo(0);
    }

    /**
     * Performs initial synchronization after login on CleverReach API.
     *
     * @throws SynchronizationException
     */
    public function initialSynchronization(): void
    {
        $this->createGroup('demomagento2.3');
        $this->synchronize();
    }

    /**
     * Performs manual synchronization.
     *
     * @throws SynchronizationException
     */
    public function manualSynchronization(): void
    {
        $this->truncateGroup();
        $this->synchronize();
    }

    /**
     * Get client account ID from database.
     *
     * @return ClientInfo
     */
    public function getClientInfo(): ClientInfo
    {
        return $this->cleverReachRepository->get('clientInfo');
    }

    /**
     * Send request to API for creating new group and set receiver group information in database.
     *
     * @param string $groupName
     */
    public function createGroup(string $groupName): void
    {
        $response = $this->synchronizationProxy->createGroup($groupName);
        if ($response->getStatus() !== 200) {
            return;
        }

        $data = new GroupInfo(json_decode($response->getBody(), true)['id']);
        $this->cleverReachRepository->set($data);
    }

    /**
     * Get receiver group information from database.
     *
     * @return GroupInfo groupID
     */
    public function getGroupInfo(): GroupInfo
    {
        if ($this->groupInfo->getId() === 0) {
            $this->groupInfo = $this->cleverReachRepository->get(CleverReachConfig::GROUP_INFO_NAME);
        }

        return $this->groupInfo;
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
        return $this->synchronizationProxy->sendReceivers($receivers, $this->getGroupInfo()->getId())->decodeBodyToArray();
    }

    /**
     * Delete receiver from API group.
     *
     * @param string $email
     */
    public function deleteReceiver(string $email): void
    {
        $this->synchronizationProxy->deleteReceiver($this->getGroupInfo()->getId(), $email);
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
    public function truncateGroup(): void
    {
        $this->synchronizationProxy->truncateGroup($this->getGroupInfo()->getId());
    }

    /**
     * Synchronize receivers.
     *
     * @throws SynchronizationException
     */
    private function synchronize()
    {
        $numberOfReceivers = $this->getNumberOfReceivers();

        $customerGroups = ceil($numberOfReceivers['customer'] / CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);
        $subscriberGroups = ceil($numberOfReceivers['subscriber'] / CleverReachConfig::NUMBER_OF_RECEIVERS_IN_GROUP);

        $this->synchronizeReceivers($customerGroups, 'customer');
        $this->synchronizeReceivers($subscriberGroups, 'subscriber');
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
