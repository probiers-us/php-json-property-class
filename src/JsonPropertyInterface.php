<?php

namespace ProbiersUs\JsonProperty;

use JsonSerializable;

interface JsonPropertyInterface extends JsonSerializable
{

    /**
     * @param array $properties
     */
    public function setJsonProperties(array $properties): void;

    /**
     * @param array $properties
     */
    public function addJsonProperties(array $properties): void;

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addJsonProperty(string $key, $value): void;

    /**
     * @param string $key
     * @return mixed
     */
    public function getJsonProperty(string $key);

    /**
     * @param string $key
     */
    public function removeJsonProperty(string $key): void;

    /**
     * @return array
     */
    public function jsonSerialize();

    /**
     * @param bool|false $resolveEmbedded
     * @return array<string, mixed>
     */
    public function getJsonProperties(bool $resolveEmbedded = false): array;
}
