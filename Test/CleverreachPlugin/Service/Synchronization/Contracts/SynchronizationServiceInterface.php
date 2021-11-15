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
     * Performs synchronization.
     */
    public function synchronize(): void;

    /**
     * Get client account ID from database.
     *
     * @return ClientInfo
     */
    public function getClientInfo(): ClientInfo;

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
}
