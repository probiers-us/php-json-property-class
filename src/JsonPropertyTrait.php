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
     * @return mixed
     */
    public function getJsonProperty(string $key)
    {
        if (false === array_key_exists($key, $this->jsonProperties)) {
            throw new JsonPropertyException(sprintf('Property with key %s not found', $key));
        }

        return $this->jsonProperties[$key];
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
     * @param bool|false $resolveEmbedded
     * @return array<string, mixed>
     */
    public function getJsonProperties(bool $resolveEmbedded = false): array
    {
        if (true === $resolveEmbedded) {
            return $this->getResolvedJsonProperties($this->jsonProperties);
        }

        return $this->jsonProperties;
    }
    /**
     * @param array $properties
     * @return array
     */
    private function getResolvedJsonProperties(array $properties): array
    {
        foreach ($properties as $key => $value) {
            if (true === is_array($value)) {
                $properties[$key] = $this->getResolvedJsonProperties($value);
            } elseif ($value instanceof JsonPropertyInterface) {
                $properties[$key] = $value->getJsonProperties(true);
            }
        }

        return $properties;
    }
}
