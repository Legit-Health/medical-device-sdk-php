<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScovidQuestionnaire;
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

    public function testToArray()
    {
        $questionnaire = new ScovidQuestionnaire(8, 10, 0, 1, 2, 3, 0, 1, 2, 3);
        $arr = $questionnaire->toArray();

        $this->assertCount(10, $arr['item']);

        $this->assertEquals(8, $arr['item']['pain']);
        $this->assertEquals(10, $arr['item']['itchiness']);
        $this->assertEquals(0, $arr['item']['fever']);
        $this->assertEquals(1, $arr['item']['cough']);
        $this->assertEquals(2, $arr['item']['cephalea']);
        $this->assertEquals(3, $arr['item']['myalgiaOrArthralgia']);
        $this->assertEquals(0, $arr['item']['malaise']);
        $this->assertEquals(1, $arr['item']['lossOfTasteOrOlfactory']);
        $this->assertEquals(2, $arr['item']['shortnessOfBreath']);
        $this->assertEquals(3, $arr['item']['otherSkinProblems']);
    }

    public function testGetName()
    {
        $questionnaire = new ScovidQuestionnaire(8, 10, 0, 1, 2, 3, 0, 1, 2, 3);
        $this->assertEquals('scovid', $questionnaire::getName());
    }
}
