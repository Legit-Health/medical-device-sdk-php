<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\UctQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class UctQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(4, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 4, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 0, 4, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 0, 0, 4);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(-1, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(5, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, -1, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 5, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 0, -1, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 0, 5, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 0, 0, -1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UctQuestionnaire(0, 0, 0, 5);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testToArray()
    {
        $uctQuestionnaire = new UctQuestionnaire(1, 2, 0, 4);
        $arr = $uctQuestionnaire->toArray();
        $this->assertEquals('uct', $arr['questionnaire']);
        $this->assertCount(4, array_keys($arr['item']));
        $this->assertEquals(1, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('physicalSymptoms', $arr['item'][0]['code']);
        $this->assertEquals(2, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('qualityOfLife', $arr['item'][1]['code']);
        $this->assertEquals(0, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('treatmentNotEnough', $arr['item'][2]['code']);
        $this->assertEquals(4, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals('overallUnderControl', $arr['item'][3]['code']);
    }

    public function testGetName()
    {
        $uctQuestionnaire = new UctQuestionnaire(1, 2, 0, 4);
        $this->assertEquals('uct', $uctQuestionnaire::getName());
    }
}
