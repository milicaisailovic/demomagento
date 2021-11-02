<?php

namespace Test\CleverreachPlugin\Http;

use Test\CleverreachPlugin\Http\DTO\Request;
use Test\CleverreachPlugin\Http\DTO\Response;

abstract class Proxy
{
    /**
     * Send HTTP GET request using cURL.
     *
     * @param Request $request
     *
     * @return Response Response
     */
    public function get(Request $request): Response
    {
        $curl = $this->setBasicRequestOptions($request);

        return $this->sendRequest($curl);
    }

    /**
     * Send HTTP POST request using cURL.
     *
     * @param Request $request
     *
     * @return Response Response
     */
    public function post(Request $request): Response
    {
        $curl = $this->setBasicRequestOptions($request);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_POST, sizeof($request->getBody()));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request->getBody()));

        return $this->sendRequest($curl);
    }

    /**
     * Send HTTP PUT request using cURL.
     *
     * @param Request $request
     *
     * @return Response Response
     */
    public function put(Request $request): Response
    {
        $curl = $this->setBasicRequestOptions($request);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request->getBody()));

        return $this->sendRequest($curl);
    }

    /**
     * Send HTTP DELETE request using cURL.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function delete(Request $request): Response
    {
        $curl = $this->setBasicRequestOptions($request);

        return $this->sendRequest($curl);
    }

    /**
     * Initialize cURL and set basic options.
     *
     * @param Request $request
     *
     * @return false|resource cURL handle
     */
    private function setBasicRequestOptions(Request $request)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $request->makeUrl());
        if ($request->getMethod() !== 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        }

        return $curl;
    }

    /**
     * Send request and close cURL handler.
     *
     * @param $curl
     *
     * @return Response
     */
    private function sendRequest($curl): Response
    {
        $response = curl_exec($curl);
        curl_close($curl);

        $responseDecoded = json_decode($response, true);
        if (isset($responseDecoded['error'])) {
            return new Response($responseDecoded['error']['code'], $response);
        }

        return new Response(200, $response);
    }
}