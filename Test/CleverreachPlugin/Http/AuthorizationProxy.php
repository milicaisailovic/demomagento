<?php

namespace Test\CleverreachPlugin\Http;

use Test\CleverreachPlugin\Service\Config\CleverReachConfig;

class AuthorizationProxy extends Proxy
{

    public function verify(string $clientId, string $clientSecret, string $code, string $redirectUri) : string
    {
        $fields["client_id"] = $clientId;
        $fields["client_secret"] = $clientSecret;
        $fields["redirect_uri"] = $redirectUri;
        $fields["grant_type"] = "authorization_code";
        $fields["code"] = $code;

        return $this->post(CleverReachConfig::TOKEN_URL, $fields);
    }
}