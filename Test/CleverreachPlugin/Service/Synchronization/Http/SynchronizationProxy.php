<?php

namespace Test\CleverreachPlugin\Service\Synchronization\Http;

use Test\CleverreachPlugin\Http\DTO\Request;
use Test\CleverreachPlugin\Http\DTO\Response;
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
     * @var string
     */
    private $accessToken;

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
        $this->accessToken = '';
    }

    /**
     * Send API request for creating new group of receivers.
     *
     * @param string $groupName
     *
     * @return Response
     */
    public function createGroup(string $groupName): Response
    {
        $body = ['name' => $groupName];
        $queryParameters = ['token' => $this->getAccessToken()];

        return $this->post(new Request('POST', CleverReachConfig::BASE_GROUP_URL, $body, $queryParameters));
    }

    /**
     * Send receivers to API.
     *
     * @param array $receivers
     * @param int $groupId
     *
     * @return Response Response
     */
    public function sendReceivers(array $receivers, int $groupId): Response
    {
        $url = CleverReachConfig::BASE_GROUP_URL . '/' . $groupId . '/receivers/upsertplus';
        $queryParameters = ['token' => $this->getAccessToken()];

        return $this->post(new Request('POST', $url, $receivers, $queryParameters));
    }

    /**
     * Delete all email addresses from group with given group ID.
     *
     * @param int $groupId
     */
    public function truncateGroup(int $groupId): void
    {
        $url = CleverReachConfig::BASE_GROUP_URL . '/' . $groupId . '/clear';
        $queryParameters = ['token' => $this->getAccessToken()];
        $this->delete(new Request('DELETE', $url, [], $queryParameters));
    }

    /**
     * Delete receiver with forwarded email from group with forwarded group ID.
     *
     * @param int $groupId
     * @param string $email
     */
    public function deleteReceiver(int $groupId, string $email): void
    {
        $url = CleverReachConfig::BASE_GROUP_URL . '/' . $groupId . '/receivers/' . $email;
        $queryParameters = ['token' => $this->getAccessToken()];
        $this->delete(new Request('DELETE', $url, [], $queryParameters));
    }

    /**
     * Return access token and save it in private class field if token isn't previously saved.
     *
     * @return string Access token
     */
    private function getAccessToken(): string
    {
        if ($this->accessToken === '') {
            $this->accessToken = $this->cleverReachRepository->get(CleverReachConfig::ACCESS_TOKEN_NAME)->getValue();
        }

        return $this->accessToken;
    }
}