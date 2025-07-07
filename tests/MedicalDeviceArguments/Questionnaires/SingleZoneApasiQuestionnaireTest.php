<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneApasiQuestionnaire;
use Throwable;
use PHPUnit\Framework\TestCase;

class SingleZoneApasiQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new SingleZoneApasiQuestionnaire(5);
        } catch (Throwable) {
            $exceptionIsThrown = false;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneApasiQuestionnaire(101);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneApasiQuestionnaire(-1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneApasiQuestionnaire(8);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testJsonSerialize()
    {
        $apasiLocalQuestionnaire = new SingleZoneApasiQuestionnaire(5);
        $arr = $apasiLocalQuestionnaire->jsonSerialize();
        $this->assertEquals(5, $arr['questionnaireResponse']['item']['surface']);
        $this->assertCount(1, array_keys($arr['questionnaireResponse']['item']));
    }

    public function testGetName()
    {
        $apasiLocalQuestionnaire = new SingleZoneApasiQuestionnaire(5);
        $this->assertEquals('apasi', $apasiLocalQuestionnaire::getName());
    }
}
