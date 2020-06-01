<?php

namespace ProbiersUs\JsonProperty;

use JsonSerializable;

interface JsonPropertyInterface extends JsonSerializable
{
    /**
     * @param string $key
     * @param mixed $value
     */
    public function addJsonProperty(string $key, $value): void;

    /**
     * @param string $key
     */
    public function removeJsonProperty(string $key): void;

    /**
     * @param bool|false $resolveEmbedded
     * @return array<string, mixed>
     */
    public function getJsonProperties(bool $resolveEmbedded = false): array;
}
