<?php

namespace Test\CleverreachPlugin\Service\Authorization;

use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Service\Authorization\Contracts\AuthorizationServiceInterface;
use Test\CleverreachPlugin\Service\Authorization\DTO\CleverReachInformation;
use Test\CleverreachPlugin\Service\Authorization\Exceptions\AuthorizationException;
use Test\CleverreachPlugin\Service\Authorization\Http\AuthorizationProxy;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class AuthorizationService implements AuthorizationServiceInterface
{
    /**
     * @var CleverReachRepository
     */
    private $repository;

    /**
     * @var AuthorizationProxy
     */
    private $proxy;

    /**
     * AuthorizationService constructor.
     *
     * @param CleverReachRepository $cleverReachRepository
     * @param AuthorizationProxy $authorizationProxy
     */
    public function __construct(
        CleverReachRepository $cleverReachRepository,
        AuthorizationProxy    $authorizationProxy
    )
    {
        $this->repository = $cleverReachRepository;
        $this->proxy = $authorizationProxy;
    }

    /**
     * Get token from repository, or null if token doesn't exist.
     *
     * @return string|null
     */
    public function get(): ?string
    {
        $resource = $this->repository->get(CleverReachConfig::ACCESS_TOKEN_NAME);
        if ($resource === null) {
            return null;
        }

        return $resource->getValue();
    }

    /**
     * Set token in repository.
     *
     * @param string $token
     */
    public function set(string $token): void
    {
        $this->repository->set(new CleverReachInformation(CleverReachConfig::ACCESS_TOKEN_NAME, $token));
    }

    /**
     * Create redirect URL after authorization.
     *
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->proxy->getRedirectUri();
    }

    /**
     * Call proxy for verification.
     *
     * @param string $code
     *
     * @return void
     *
     * @throws AuthorizationException
     */
    public function verify(string $code): void
    {
        $response = $this->proxy->authorize($code);

        if ($response->getStatus() < 200 || $response->getStatus() >= 300) {
            throw new AuthorizationException('cUrl error: ' . $response->decodeBodyToArray()['error'],
                $response->getStatus());
        }

        $accessToken = $response->decodeBodyToArray()['access_token'];
        $this->set($accessToken);
        $clientInfo = $this->proxy->getClientAccountInformation($accessToken)->getBody();
        $this->repository->set(new CleverReachInformation('clientInfo', $clientInfo));
    }
}
