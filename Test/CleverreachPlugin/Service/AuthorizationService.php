<?php

namespace Test\CleverreachPlugin\Service;

use Test\CleverreachPlugin\Repository\CleverReachRepository;

class AuthorizationService
{
    private CleverReachRepository $repository;

    public function __construct()
    {
        $this->repository = new CleverReachRepository();
    }

    public function get(string $clientId) : string
    {
        return $this->repository->getToken($clientId);
    }

    public function set(string $clientId, string $token) : void
    {
        $this->repository->setToken($clientId, $token);
    }

    public function getRedirectUri(string $siteUrl, string $class) : string
    {
        $classRoute = explode("\\", $class);
        $className = strtolower(end($classRoute));
        $classDirectory = strtolower(prev($classRoute));

        return $siteUrl . '/test/' . $classDirectory . '/' . $className;
    }
}