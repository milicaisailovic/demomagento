<?php

namespace Test\CleverreachPlugin\Http;

abstract class Proxy
{
    /**
     * Send HTTP GET request using cURL.
     *
     * @param string $url
     *
     * @return string Response
     */
    public function get(string $url) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        return $this->sendRequest($curl);
    }

    /**
     * Send HTTP POST request using cURL.
     *
     * @param string $url
     * @param array $parameters
     *
     * @return string Response
     */
    public function post(string $url, array $parameters) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl,CURLOPT_POST, sizeof($parameters));
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($parameters));

        return $this->sendRequest($curl);
    }

    /**
     * Send HTTP PUT request using cURL.
     *
     * @param string $url
     * @param array $parameters
     *
     * @return string Response
     */
    public function put(string $url, array $parameters) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));

        return $this->sendRequest($curl);
    }

    /**
     * Send HTTP DELETE request using cURL.
     *
     * @param string $url
     *
     * @return string
     */
    public function delete(string $url) : string
    {
        $curl = $this->setBasicRequestOptions($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->sendRequest($curl);
    }

    /**
     * Initialize cURL and set basic options.
     *
     * @param string $url
     *
     * @return false|resource cURL handle
     */
    private function setBasicRequestOptions(string $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        return $curl;
    }

    /**
     * Send request and close cURL handler.
     *
     * @param $curl
     *
     * @return string
     */
    private function sendRequest($curl) : string
    {
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
