<?php

namespace Test\CleverreachPlugin\Service\DataModel;

class CleverReachInformation
{
    private string $name;
    private string $value;

    /**
     * @param string $name
     * @param string|null $value
     */
    public function __construct(string $name, ?string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * Convert object to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return ['name' => $this->name, 'value' => $this->value];
    }
}