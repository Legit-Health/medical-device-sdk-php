<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\AuasLocalQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class AuasLocalQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new AuasLocalQuestionnaire(3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new AuasLocalQuestionnaire(0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new AuasLocalQuestionnaire(-1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new AuasLocalQuestionnaire(10);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testToArray()
    {
        $auasLocalQuestionnaire = new AuasLocalQuestionnaire(3);
        $arr = $auasLocalQuestionnaire->toArray();

        $this->assertEquals('auasLocal', $arr['questionnaire']);
        $this->assertCount(1, array_keys($arr['item']));

        $this->assertEquals(3, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('itchiness', $arr['item'][0]['code']);
    }

    public function testGetName()
    {
        $auasLocalQuestionnaire = new AuasLocalQuestionnaire(3);
        $this->assertEquals('auasLocal', $auasLocalQuestionnaire::getName());
    }
}
