<?php

namespace Test\CleverreachPlugin\Service\Authorization;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\DataModel\CleverReachInformation;

class AuthorizationService
{
    private CleverReachRepository $repository;
    private AuthorizationProxy $proxy;

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
        $objectManager = ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        return $storeManager->getStore()->getBaseUrl() . 'front/callback/index';
    }

    /**
     * Call proxy for verification.
     *
     * @param string $code
     *
     * @return string
     */
    public function verify(string $code): string
    {
        return $this->proxy->verify($code);
    }
}
