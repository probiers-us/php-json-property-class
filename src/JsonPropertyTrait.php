<?php

/**
 * This code is licensed under the BSD 3-Clause License.
 *
 * Copyright (c) 2020, Nick Chiu
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * * Neither the name of the copyright holder nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

declare(strict_types=1);

namespace ProbiersUs\JsonProperty;

trait JsonPropertyTrait
{
    protected array $jsonProperties = [];

    /**
     * @param array<string, mixed> $properties
     */
    public function setJsonProperties(array $properties): void
    {
        $this->jsonProperties = $properties;
    }

    /**
     * @param array<string, mixed> $properties
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
     * @return array<string, mixed>
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
