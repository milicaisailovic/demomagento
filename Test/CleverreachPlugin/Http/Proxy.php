<?php

namespace Test\CleverreachPlugin\Http;

abstract class Proxy
{
    public function get(string $url) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        return $this->sendRequest($curl);
    }

    public function post(string $url, array $parameters) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl,CURLOPT_POST, sizeof($parameters));
        curl_setopt($curl,CURLOPT_POSTFIELDS, $parameters);

        return $this->sendRequest($curl);
    }

    public function put(string $url, array $parameters) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($parameters));

        return $this->sendRequest($curl);
    }

    public function delete(string $url) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->sendRequest($curl);
    }

    private function setBasicRequestOptions(string $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        return $curl;
    }

    private function sendRequest($curl) : string
    {
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}