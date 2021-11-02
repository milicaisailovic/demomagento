<?php

namespace Test\CleverreachPlugin\Service\Authorization\Http;

use Magento\Framework\App\ObjectManager;
use Test\CleverreachPlugin\Http\DTO\Request;
use Test\CleverreachPlugin\Http\DTO\Response;
use Test\CleverreachPlugin\Http\Proxy;
use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class AuthorizationProxy extends Proxy
{
    /**
     * Send HTTP POST request to API for logging in.
     *
     * @param string $code
     *
     * @return Response
     */
    public function authorize(string $code): Response
    {
        $fields["redirect_uri"] = $this->getRedirectUri();
        $fields["client_id"] = CleverReachConfig::CLIENT_ID;
        $fields["client_secret"] = CleverReachConfig::CLIENT_SECRET;
        $fields["grant_type"] = "authorization_code";
        $fields["code"] = $code;

        return $this->post(new Request('POST', CleverReachConfig::TOKEN_URL, $fields));
    }

    /**
     * Send HTTP GET request for client information.
     *
     * @param string $token
     *
     * @return Response
     */
    public function getClientAccountInformation(string $token): Response
    {
        return $this->get(new Request('GET', CleverReachConfig::DEBUG_URL, [], ['token' => $token]));
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
}