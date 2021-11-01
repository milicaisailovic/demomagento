<?php

namespace Test\CleverreachPlugin\Service\DataModel;

class Receiver implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var int
     */
    private $registered;

    /**
     * @var int
     */
    private $deactivated;

    public function __construct(
        int    $id,
        string $email,
        int    $registered,
        int    $deactivated,
        string $firstname = '',
        string $lastname = ''
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->registered = $registered;
        $this->deactivated = $deactivated;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->active = $deactivated === 0;
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
     * @return int
     */
    public function getRegistered(): int
    {
        return $this->registered;
    }

    /**
     * @param int $registered
     */
    public function setRegistered(int $registered): void
    {
        $this->registered = $registered;
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
     * @return int
     */
    public function getDeactivated(): int
    {
        return $this->deactivated;
    }

    /**
     * @param int $deactivated
     */
    public function setDeactivated(int $deactivated): void
    {
        $this->deactivated = $deactivated;
    }

    /**
     * Return object ready for JSON encoding.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $globalAttributes = [];
        if ($this->firstname !== '') {
            $globalAttributes['firstname'] = $this->firstname;
        }

        if ($this->lastname !== '') {
            $globalAttributes['lastname'] = $this->lastname;
        }

        return ['id' => $this->id, 'email' => $this->email, 'active' => $this->active,
            'registered' => $this->registered, 'deactivated' => $this->deactivated,
            'activated' => $this->registered, 'global_attributes' => $globalAttributes];
    }

    /**
     * Convert object to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return ['id' => $this->id, 'email' => $this->email, 'active' => $this->active,
            'firstname' => $this->firstname, 'lastname' => $this->lastname];
    }
}
