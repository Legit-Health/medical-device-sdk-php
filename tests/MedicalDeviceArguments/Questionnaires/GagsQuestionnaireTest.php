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

        $this->assertEquals(0, $arr['item']['forehead']);
        $this->assertEquals(1, $arr['item']['rightCheek']);
        $this->assertEquals(2, $arr['item']['leftCheek']);
        $this->assertEquals(3, $arr['item']['nose']);
        $this->assertEquals(4, $arr['item']['chin']);
        $this->assertEquals(1, $arr['item']['chestAndUpperBack']);

        $gagsQuestionnaire = new GagsQuestionnaire(4, 3, 2, 1, 0, 4);
        $arr = $gagsQuestionnaire->toArray();

        $this->assertEquals(4, $arr['item']['forehead']);
        $this->assertEquals(3, $arr['item']['rightCheek']);
        $this->assertEquals(2, $arr['item']['leftCheek']);
        $this->assertEquals(1, $arr['item']['nose']);
        $this->assertEquals(0, $arr['item']['chin']);
        $this->assertEquals(4, $arr['item']['chestAndUpperBack']);
    }

    public function testGetName()
    {
        $gagsQuestionnaire = new GagsQuestionnaire(0, 1, 2, 3, 4, 1);
        $this->assertEquals('gags', $gagsQuestionnaire::getName());
    }
}
