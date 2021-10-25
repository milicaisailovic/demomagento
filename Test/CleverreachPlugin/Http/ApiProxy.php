<?php

namespace Test\CleverreachPlugin\Http;

use Test\CleverreachPlugin\Service\Authorization\AuthorizationService;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\Synchronization\SynchronizationService;

class ApiProxy extends Proxy
{
    private AuthorizationService $authorizationService;
    private SynchronizationService $synchronizationService;

    /**
     * ApiProxy constructor.
     */
    public function __construct()
    {
        $this->authorizationService = new AuthorizationService();
        $this->synchronizationService = new SynchronizationService();
    }

    /**
     * Send API request for creating new group of receivers.
     *
     * @param string $groupName
     *
     * @return string Serialized information for created group
     */
    public function createGroup(string $groupName): string
    {
        $url = CleverReachConfig::BASE_GROUP_URL . '?token=' . $this->authorizationService->get();

        return $this->post($url, ['name' => $groupName]);
    }

    /**
     * Send receivers to API.
     *
     * @param array $receivers
     *
     * @return array Response
     */
    public function sendReceivers(array $receivers): array
    {
        $groupId = $this->synchronizationService->getGroupInfo()['id'];
        $url = CleverReachConfig::BASE_GROUP_URL . '/' . $groupId . '/receivers/upsertplus?token='
            . $this->authorizationService->get();

        return json_decode($this->post($url, $receivers), true);
    }
}