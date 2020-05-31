<?php

namespace ProbiersUs\JsonProperty\Tests;

use PHPUnit\Framework\TestCase;
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
}
