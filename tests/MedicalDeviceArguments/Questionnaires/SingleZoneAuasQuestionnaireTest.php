<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAuasQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class SingleZoneAuasQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new SingleZoneAuasQuestionnaire(3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAuasQuestionnaire(0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAuasQuestionnaire(-1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAuasQuestionnaire(10);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testJsonSerialize()
    {
        $auasLocalQuestionnaire = new SingleZoneAuasQuestionnaire(3);
        $arr = $auasLocalQuestionnaire->jsonSerialize();

        $this->assertCount(1, array_keys($arr['questionnaireResponse']['item']));
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['pruritus']);
    }

    public function testGetName()
    {
        $singleZoneAuasQuestionnaire = new SingleZoneAuasQuestionnaire(3);
        $this->assertEquals('auas', $singleZoneAuasQuestionnaire::getName());
    }
}
