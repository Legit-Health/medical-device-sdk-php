<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAeasiQuestionnaire;
use Throwable;
use PHPUnit\Framework\TestCase;

class SingleZoneAeasiQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new SingleZoneAeasiQuestionnaire(101, 5);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAeasiQuestionnaire(50, -1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAeasiQuestionnaire(-1, 10);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testJsonSerialize()
    {
        $singleZoneAeasiQuestionnaire = new SingleZoneAeasiQuestionnaire(5, 30);
        $arr = $singleZoneAeasiQuestionnaire->jsonSerialize();
        $this->assertEquals(5, $arr['questionnaireResponse']['item']['surface']);
        $this->assertEquals(30, $arr['questionnaireResponse']['item']['patientAge']);
        $this->assertCount(2, array_keys($arr['questionnaireResponse']['item']));
    }

    public function testGetName()
    {
        $singleZoneAeasiQuestionnaire = new SingleZoneAeasiQuestionnaire(5, 30);
        $this->assertEquals('aesii', $singleZoneAeasiQuestionnaire::getName());
    }
}
