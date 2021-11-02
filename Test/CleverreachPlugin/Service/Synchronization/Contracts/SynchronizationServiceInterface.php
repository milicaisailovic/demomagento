<?php

namespace Test\CleverreachPlugin\Service\Synchronization\Contracts;

interface SynchronizationServiceInterface
{
    /**
     * Get client account ID from database.
     *
     * @return int
     */
    public function getClientId(): int;

    /**
     * Send request to API for creating new group and set receiver group information in database.
     *
     * @param string $groupName
     */
    public function createGroup(string $groupName): void;

    /**
     * Get receiver group information from database.
     */
    public function getGroupId(): int;

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