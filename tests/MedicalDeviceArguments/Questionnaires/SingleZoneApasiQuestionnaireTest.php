<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\SingleZoneApasiQuestionnaire;
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
    }

    public function testToArray()
    {
        $apasiLocalQuestionnaire = new SingleZoneApasiQuestionnaire(5);
        $arr = $apasiLocalQuestionnaire->toArray();
        $this->assertEquals(5, $arr['item']['surface']);
        $this->assertCount(1, array_keys($arr['item']));
    }

    public function testGetName()
    {
        $apasiLocalQuestionnaire = new SingleZoneApasiQuestionnaire(5);
        $this->assertEquals('apasi', $apasiLocalQuestionnaire::getName());
    }
}
