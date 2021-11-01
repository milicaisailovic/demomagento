<?php

namespace Test\CleverreachPlugin\Service\Synchronization;

use Test\CleverreachPlugin\Http\Proxy;
use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class SynchronizationProxy extends Proxy
{
    /**
     * @var CleverReachRepository
     */
    private $cleverReachRepository;

    /**
     * SynchronizationProxy constructor.
     *
     * @param CleverReachRepository $cleverReachRepository
     */
    public function __construct(
        CleverReachRepository $cleverReachRepository
    )
    {
        $this->cleverReachRepository = $cleverReachRepository;
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
        $url = CleverReachConfig::BASE_GROUP_URL . '?token=' .
            $this->cleverReachRepository->get(CleverReachConfig::ACCESS_TOKEN_NAME)->getValue();

        return $this->post($url, ['name' => $groupName]);
    }

    /**
     * Send receivers to API.
     *
     * @param array $receivers
     * @param int $groupId
     *
     * @return array Response
     */
    public function sendReceivers(array $receivers, int $groupId): array
    {
        $url = CleverReachConfig::BASE_GROUP_URL . '/' . $groupId . '/receivers/upsertplus?token='
            . $this->cleverReachRepository->get(CleverReachConfig::ACCESS_TOKEN_NAME)->getValue();

        return json_decode($this->post($url, $receivers), true);
    }

    /**
     * Delete all email addresses from group with given group ID.
     *
     * @param int $groupId
     */
    public function truncateGroup(int $groupId): void
    {
        $url = CleverReachConfig::BASE_GROUP_URL . '/' . $groupId . '/clear?token='
            . $this->cleverReachRepository->get(CleverReachConfig::ACCESS_TOKEN_NAME)->getValue();
        $this->delete($url);
    }

    /**
     * Delete receiver with forwarded email from group with forwarded group ID.
     *
     * @param int $groupId
     * @param string $email
     */
    public function deleteReceiver(int $groupId, string $email): void
    {
        $url = CleverReachConfig::BASE_GROUP_URL . '/' . $groupId . '/receivers/' . $email . '?token='
            . $this->cleverReachRepository->get(CleverReachConfig::ACCESS_TOKEN_NAME)->getValue();
        $this->delete($url);
    }
}