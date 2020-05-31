<?php

namespace ProbiersUs\JsonProperty;

trait JsonPropertyTrait
{
    protected array $jsonProperties = [];

    /**
     * @param array $properties
     */
    public function setJsonProperties(array $properties): void
    {
        $this->jsonProperties = $properties;
    }

    /**
     * @param array $properties
     */
    public function addJsonProperties(array $properties): void
    {
        $this->jsonProperties = array_merge($this->jsonProperties, $properties);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addJsonProperty(string $key, $value): void
    {
        $this->jsonProperties[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function removeJsonProperty(string $key): void
    {
        unset($this->jsonProperties[$key]);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getJsonProperties();
    }

    /**
     * @return array<string, mixed>
     */
    public function getJsonProperties(): array
    {
        return $this->jsonProperties;
    }
}
