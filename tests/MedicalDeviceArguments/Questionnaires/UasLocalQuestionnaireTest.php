<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\UasLocalQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class UasLocalQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(3, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(0, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(-1, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(10, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);


        // hive
        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(3, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(0, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(0, 4);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasLocalQuestionnaire(0, -1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testToArray()
    {
        $auasLocalQuestionnaire = new UasLocalQuestionnaire(3, 2);
        $arr = $auasLocalQuestionnaire->toArray();
        $this->assertCount(2, array_keys($arr['item']));
        $this->assertEquals('uasLocal', $arr['questionnaire']);
        $this->assertEquals(3, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('itchiness', $arr['item'][0]['code']);
        $this->assertEquals(2, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('hive', $arr['item'][1]['code']);
    }

    public function testGetName()
    {
        $auasLocalQuestionnaire = new UasLocalQuestionnaire(3, 2);
        $this->assertEquals('uasLocal', $auasLocalQuestionnaire::getName());
    }
}
