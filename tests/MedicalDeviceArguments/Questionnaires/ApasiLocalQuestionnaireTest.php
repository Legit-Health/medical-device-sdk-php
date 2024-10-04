<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ApasiLocalQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class ApasiLocalQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new ApasiLocalQuestionnaire(5);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ApasiLocalQuestionnaire(10);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ApasiLocalQuestionnaire(-1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testToArray()
    {
        $apasiLocalQuestionnaire = new ApasiLocalQuestionnaire(5);
        $arr = $apasiLocalQuestionnaire->toArray();
        $this->assertEquals('apasiLocal', $arr['questionnaire']);
        $this->assertCount(1, array_keys($arr['item']));

        $this->assertEquals(5, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('surface', $arr['item'][0]['code']);
    }

    public function testGetName()
    {
        $apasiLocalQuestionnaire = new ApasiLocalQuestionnaire(5);
        $this->assertEquals('apasiLocal', $apasiLocalQuestionnaire::getName());
    }
}
