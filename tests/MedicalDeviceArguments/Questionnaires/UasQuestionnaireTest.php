<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\UasQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class UasQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(3, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(0, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(-1, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(10, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);


        // wheals
        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(3, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(0, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(0, 4);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new UasQuestionnaire(0, -1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testJsonSerialize()
    {
        $auasLocalQuestionnaire = new UasQuestionnaire(3, 2);
        $arr = $auasLocalQuestionnaire->jsonSerialize();

        $this->assertCount(2, $arr['questionnaireResponse']['item']);

        $this->assertEquals(3, $arr['questionnaireResponse']['item']['pruritus']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['wheals']);
    }

    public function testGetName()
    {
        $auasLocalQuestionnaire = new UasQuestionnaire(3, 2);
        $this->assertEquals('uas', $auasLocalQuestionnaire::getName());
    }
}
