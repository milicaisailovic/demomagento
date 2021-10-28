<?php

namespace Test\CleverreachPlugin\Service\Authorization;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\Repository\CleverReachRepository;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;
use Test\CleverreachPlugin\Service\DataModel\CleverReachInformation;
use Test\CleverreachPlugin\Service\Exceptions\AuthorizationException;

class AuthorizationService
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
        $objectManager = ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        return $storeManager->getStore()->getBaseUrl() . 'front/callback/index';
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
        $response = $this->proxy->verify($code);
        $responseDecoded = json_decode($response, true);

        if (array_key_exists('error', $responseDecoded)) {
            throw new AuthorizationException('cUrl error: ' . $responseDecoded['error'], 401);
        }

        $this->set($responseDecoded['access_token']);
        $clientInfo = $this->proxy->getClientAccountInformation($responseDecoded['access_token']);
        $this->repository->set(new CleverReachInformation('clientInfo', $clientInfo));
    }
}
