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

    public function testJsonSerialize()
    {
        $uctQuestionnaire = new UctQuestionnaire(1, 2, 0, 4);
        $arr = $uctQuestionnaire->jsonSerialize();

        $this->assertCount(4, $arr['questionnaireResponse']['item']);

        $this->assertEquals(1, $arr['questionnaireResponse']['item']['physicalSymptoms']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['qualityOfLife']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['treatmentNotEnough']);
        $this->assertEquals(4, $arr['questionnaireResponse']['item']['overallUnderControl']);
    }

    public function testGetName()
    {
        $uctQuestionnaire = new UctQuestionnaire(1, 2, 0, 4);
        $this->assertEquals('uct', $uctQuestionnaire::getName());
    }
}
