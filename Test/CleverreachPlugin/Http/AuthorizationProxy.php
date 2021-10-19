<?php

namespace Test\CleverreachPlugin\Http;

class AuthorizationProxy extends Proxy
{
    const TOKEN_URL = "https://rest.cleverreach.com/oauth/token.php";

    public function verify(string $clientId, string $clientSecret, string $code, string $redirectUri) : string
    {
        $fields["client_id"] = $clientId;
        $fields["client_secret"] = $clientSecret;
        $fields["redirect_uri"] = $redirectUri;
        $fields["grant_type"] = "authorization_code";
        $fields["code"] = $code;

        return $this->post(self::TOKEN_URL, $fields);
    }
}