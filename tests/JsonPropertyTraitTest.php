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

namespace ProbiersUs\JsonProperty\Tests;

use PHPUnit\Framework\TestCase;
use ProbiersUs\JsonProperty\JsonPropertyException;
use ProbiersUs\JsonProperty\Tests\Helper\JsonPropertyTestClass;

class JsonPropertyTraitTest extends TestCase
{
    public function testSetProperties() {
        $sot = new JsonPropertyTestClass();

        $testArgument = ['test', 'test2'];
        $testArgument2 = ['test3'];

        $sot->setJsonProperties($testArgument);
        self::assertEquals($testArgument, $sot->getJsonProperties());

        $sot->setJsonProperties($testArgument2);
        self::assertEquals($testArgument2, $sot->getJsonProperties());
    }

    public function testAddProperties() {
        $sot = new JsonPropertyTestClass();

        $testArgument = ['test', 'test2'];
        $testArgument2 = ['test3'];

        $sot->addJsonProperties($testArgument);
        self::assertEquals($testArgument, $sot->getJsonProperties());

        $sot->addJsonProperties($testArgument2);
        self::assertEquals(array_merge($testArgument, $testArgument2), $sot->getJsonProperties());
    }

    public function testAddAndRemoveProperty() {
        $sot = new JsonPropertyTestClass();

        $expectedResult = ['key' => 'value'];

        $sot->addJsonProperty('key', 'value');
        self::assertEquals($expectedResult, $sot->getJsonProperties());

        $sot->removeJsonProperty('key');
        self::assertEquals([], $sot->getJsonProperties());
    }

    public function testGetNonExistingProperty() {
        $sot = new JsonPropertyTestClass();

        self::expectException(JsonPropertyException::class);

        $sot->getJsonProperty('key');
    }

    public function testGetProperty() {
        $sot = new JsonPropertyTestClass();
        $sot->addJsonProperty('key', 'value');

        self::assertEquals('value', $sot->getJsonProperty('key'));
    }

    public function testJsonSerialize() {
        $sot = new JsonPropertyTestClass();

        $expectedResult = ['bacon' => 'love'];

        $sot->addJsonProperty('bacon', 'love');
        self::assertEquals($expectedResult, $sot->jsonSerialize());
    }

    public function testJsonSerializeWithEmbedded() {
        $sot = new JsonPropertyTestClass();
        $injectedSot = new JsonPropertyTestClass();

        $testArray = [
            'key' => ['foo' => 'bar'],
            'mickey' => ['mouse' => $injectedSot],
        ];
        $expectedResult = json_encode(
            [
                'key' => ['foo' => 'bar'],
                'mickey' => ['mouse' => ['bacon' => 'love']],
            ]
        );

        $injectedSot->addJsonProperty('bacon', 'love');
        $sot->addJsonProperties($testArray);
        self::assertEquals($expectedResult, json_encode($sot));
    }

    public function testGetJsonProperties() {
        $sot = new JsonPropertyTestClass();
        $injectedSot = new JsonPropertyTestClass();

        $testArray = [
            'key' => ['foo' => 'bar'],
            'mickey' => ['mouse' => $injectedSot],
        ];

        $injectedSot->addJsonProperty('bacon', 'love');
        $sot->addJsonProperties($testArray);
        self::assertEquals($testArray, $sot->getJsonProperties());
    }

    public function testGetJsonPropertiesResolveEmbedded() {
        $sot = new JsonPropertyTestClass();
        $injectedSot1 = new JsonPropertyTestClass();
        $injectedSot2 = new JsonPropertyTestClass();
        $injectedSot2->addJsonProperty('book', 'worm');


        $testArray = [
            'key' => ['foo' => 'bar'],
            'mickey' => ['mouse' => $injectedSot1],
        ];
        $expectedResult = [
            'key' => ['foo' => 'bar'],
            'mickey' => [
                'mouse' => [
                    'bacon' => 'love',
                    'another' => [
                        'book' => 'worm'
                    ]
                ]
            ],
        ];

        $injectedSot1->addJsonProperty('bacon', 'love');
        $injectedSot1->addJsonProperty('another', $injectedSot2);
        $sot->addJsonProperties($testArray);
        self::assertEquals($expectedResult, $sot->getJsonProperties(true));
    }
}
