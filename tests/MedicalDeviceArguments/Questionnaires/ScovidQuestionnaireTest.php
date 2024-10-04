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

        $this->assertCount(10, array_keys($arr['item']));
        $this->assertEquals('scovid', $arr['questionnaire']);

        $this->assertEquals(8, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('pain', $arr['item'][0]['code']);

        $this->assertEquals(10, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('itchiness', $arr['item'][1]['code']);

        $this->assertEquals(0, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('fever', $arr['item'][2]['code']);

        $this->assertEquals(1, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals('cough', $arr['item'][3]['code']);

        $this->assertEquals(2, $arr['item'][4]['answer'][0]['value']);
        $this->assertEquals('cephalea', $arr['item'][4]['code']);

        $this->assertEquals(3, $arr['item'][5]['answer'][0]['value']);
        $this->assertEquals('myalgiaOrArthralgia', $arr['item'][5]['code']);

        $this->assertEquals(0, $arr['item'][6]['answer'][0]['value']);
        $this->assertEquals('malaise', $arr['item'][6]['code']);

        $this->assertEquals(1, $arr['item'][7]['answer'][0]['value']);
        $this->assertEquals('lossOfTasteOrOlfactory', $arr['item'][7]['code']);

        $this->assertEquals(2, $arr['item'][8]['answer'][0]['value']);
        $this->assertEquals('shortnessOfBreath', $arr['item'][8]['code']);

        $this->assertEquals(3, $arr['item'][9]['answer'][0]['value']);
        $this->assertEquals('otherSkinProblems', $arr['item'][9]['code']);
    }

    public function testGetName()
    {
        $questionnaire = new ScovidQuestionnaire(8, 10, 0, 1, 2, 3, 0, 1, 2, 3);
        $this->assertEquals('scovid', $questionnaire::getName());
    }
}
