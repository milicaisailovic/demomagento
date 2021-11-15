<?php

namespace Test\CleverreachPlugin\Service\Synchronization\Contracts;

use Test\CleverreachPlugin\Service\Authorization\DTO\ClientInfo;
use Test\CleverreachPlugin\Service\Synchronization\DTO\GroupInfo;

interface SynchronizationServiceInterface
{
    /**
     * Performs initial synchronization after login on CleverReach API.
     */
    public function initialSynchronization(): void;

    /**
     * Performs manual synchronization.
     */
    public function manualSynchronization(): void;

    /**
     * Get client account ID from database.
     *
     * @return int
     */
    public function getClientInfo(): ClientInfo;

    /**
     * Send request to API for creating new group and set receiver group information in database.
     *
     * @param string $groupName
     */
    public function createGroup(string $groupName): void;

    /**
     * Get receiver group information from database.
     */
    public function getGroupInfo(): GroupInfo;

    /**
     * Get edited customer from database and send it to API.
     *
     * @param string $email
     *
     * @return void
     */
    public function sendEditedInformation(string $email): void;

    /**
     * Get part of receivers from customers.
     *
     * @param int $groupNumber
     *
     * @return array Mapped customers to CleverReach receivers
     */
    public function getReceiversFromCustomers(int $groupNumber): array;

    /**
     * Get part of receivers from subscribers.
     *
     * @param int $groupNumber
     *
     * @return array Mapped subscribers to CleverReach receivers
     */
    public function getReceiversFromSubscribers(int $groupNumber): array;

    /**
     * Get number of receivers, separately customers and subscribers number.
     *
     * @return array
     */
    public function getNumberOfReceivers(): array;

    /**
     * Forward receivers to API proxy.
     *
     * @param array $receivers
     *
     * @return array Response
     */
    public function sendReceivers(array $receivers): array;

    /**
     * Delete receiver from API group.
     *
     * @param string $email
     */
    public function deleteReceiver(string $email): void;

    /**
     * Get receivers from database and send them to API.
     *
     * @param int $numberOfGroups
     * @param string $type
     */
    public function synchronizeReceivers(int $numberOfGroups, string $type): void;

    /**
     * Delete all emails in API group.
     */
    public function truncateGroup(): void;
}
