<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\ScovidQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class ScovidQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new ScovidQuestionnaire(1, 8, 0, 1, 2, 3, 0, 1, 2, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScovidQuestionnaire(1, -1, 0, 1, 2, 4, 0, 1, 2, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScovidQuestionnaire(1, -1, 0, 1, 2, 3, 0, 1, 2, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScovidQuestionnaire(1, 11, 0, 1, 2, 3, 0, 1, 2, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScovidQuestionnaire(11, 10, 0, 1, 2, 3, 0, 1, 2, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScovidQuestionnaire(-1, 10, 0, 1, 2, 3, 0, 1, 2, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testJsonSerialize()
    {
        $questionnaire = new ScovidQuestionnaire(8, 10, 0, 1, 2, 3, 0, 1, 2, 3);
        $arr = $questionnaire->jsonSerialize();

        $this->assertCount(10, $arr['questionnaireResponse']['item']);

        $this->assertEquals(8, $arr['questionnaireResponse']['item']['pain']);
        $this->assertEquals(10, $arr['questionnaireResponse']['item']['itchiness']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['fever']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['cough']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['cephalea']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['myalgiaOrArthralgia']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['malaise']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['lossOfTasteOrOlfactory']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['shortnessOfBreath']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['otherSkinProblems']);
    }

    public function testGetName()
    {
        $questionnaire = new ScovidQuestionnaire(8, 10, 0, 1, 2, 3, 0, 1, 2, 3);
        $this->assertEquals('scovid', $questionnaire::getName());
    }
}
