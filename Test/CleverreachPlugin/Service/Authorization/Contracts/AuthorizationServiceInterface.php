<?php

namespace Test\CleverreachPlugin\Service\Authorization\Contracts;

interface AuthorizationServiceInterface
{
    /**
     * Get token from repository, or null if token doesn't exist.
     *
     * @return string|null
     */
    public function get(): ?string;


    /**
     * Set token in repository.
     *
     * @param string $token
     */
    public function set(string $token): void;

    /**
     * Create redirect URL after authorization.
     *
     * @return string
     */
    public function getRedirectUri(): string;

    /**
     * Call proxy for verification.
     *
     * @param string $code
     *
     * @return void
     */
    public function verify(string $code): void;
}
