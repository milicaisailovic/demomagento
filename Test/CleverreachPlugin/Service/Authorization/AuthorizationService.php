<?php

namespace Test\CleverreachPlugin\Service\Authorization;

use Test\CleverreachPlugin\Repository\CleverReachRepository;

class AuthorizationService
{
    private CleverReachRepository $repository;

    /**
     * AuthorizationService constructor.
     */
    public function __construct()
    {
        $this->repository = new CleverReachRepository();
    }

    /**
     * Get token from repository, or null if token doesn't exist.
     *
     * @return string|null
     */
    public function get() : ?string
    {
        return $this->repository->getToken();
    }

    /**
     * Set token in repository.
     *
     * @param string $token
     */
    public function set(string $token) : void
    {
        $this->repository->setToken($token);
    }

    /**
     * Create redirect URL after authorization.
     *
     * @param string $siteUrl
     * @param string $class
     *
     * @return string
     */
    public function getRedirectUri(string $siteUrl, string $class) : string
    {
        $classRoute = explode("\\", $class);
        $className = strtolower(end($classRoute));
        $classDirectory = strtolower(prev($classRoute));

        return $siteUrl . '/front/' . $classDirectory . '/' . $className;
    }
}