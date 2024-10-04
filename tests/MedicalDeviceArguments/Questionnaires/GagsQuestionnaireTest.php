<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\GagsQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class GagsQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        // Test valid inputs
        $exceptionIsThrown = false;
        try {
            new GagsQuestionnaire(0, 0, 0, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new GagsQuestionnaire(4, 4, 4, 4, 4, 4);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new GagsQuestionnaire(random_int(0, 4), random_int(0, 4), random_int(0, 4), random_int(0, 4), random_int(0, 4), random_int(0, 4));
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        // Test out-of-range inputs
        for ($i = 0; $i < 6; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 6, 5); // Out of bounds for all fields
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 0; // Keep some fields valid
                }
                new GagsQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $expectedMessage = sprintf('%s should be between 0 and 4', ['forehead', 'rightCheek', 'leftCheek', 'nose', 'chin', 'chestAndUpperBack'][$i]);
                $this->assertEquals($expectedMessage, $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }
    }

    public function testToArray()
    {
        $gagsQuestionnaire = new GagsQuestionnaire(0, 1, 2, 3, 4, 1);
        $arr = $gagsQuestionnaire->toArray();

        $this->assertEquals('gags', $arr['questionnaire']);
        $this->assertCount(6, array_keys($arr['item']));

        $this->assertEquals(0, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('forehead', $arr['item'][0]['code']);
        $this->assertEquals(1, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('rightCheek', $arr['item'][1]['code']);
        $this->assertEquals(2, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('leftCheek', $arr['item'][2]['code']);
        $this->assertEquals(3, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals('nose', $arr['item'][3]['code']);
        $this->assertEquals(4, $arr['item'][4]['answer'][0]['value']);
        $this->assertEquals('chin', $arr['item'][4]['code']);
        $this->assertEquals(1, $arr['item'][5]['answer'][0]['value']);
        $this->assertEquals('chestAndUpperBack', $arr['item'][5]['code']);

        $gagsQuestionnaire = new GagsQuestionnaire(4, 3, 2, 1, 0, 4);
        $arr = $gagsQuestionnaire->toArray();
        $this->assertCount(6, array_keys($arr['item']));
        $this->assertEquals(4, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals(3, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals(2, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals(1, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals(0, $arr['item'][4]['answer'][0]['value']);
        $this->assertEquals(4, $arr['item'][5]['answer'][0]['value']);
    }

    public function testGetName()
    {
        $gagsQuestionnaire = new GagsQuestionnaire(0, 1, 2, 3, 4, 1);
        $this->assertEquals('gags', $gagsQuestionnaire::getName());
    }
}
