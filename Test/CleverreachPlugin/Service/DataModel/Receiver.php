<?php

namespace Test\CleverreachPlugin\Service\DataModel;

class Receiver implements \JsonSerializable
{
    private int $id;
    private string $email;
    private string $firstname;
    private string $lastname;
    private bool $active;

    public function __construct(int $id, string $email, bool $active = false, string $firstname = '', string $lastname = '')
    {
        $this->id = $id;
        $this->email = $email;
        $this->active = $active;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * Return object ready for JSON encoding.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['id' => $this->id, 'email' => $this->email, 'active' => $this->active,
            'global_attributes' => ['firstname' => $this->firstname, 'lastname' => $this->lastname]];
    }
}