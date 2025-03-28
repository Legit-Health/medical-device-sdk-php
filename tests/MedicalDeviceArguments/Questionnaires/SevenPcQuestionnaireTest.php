<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\SevenPcQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class SevenPcQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $questionsOrder = [
            'irregularSize',
            'irregularPigmentation',
            'irregularBorder',
            'inflammation',
            'largerThanOtherLesions',
            'itchOrAltered',
            'crustedOrBleeding'
        ];
        $exceptionIsThrown = false;
        try {
            new SevenPcQuestionnaire(0, 0, 0, 0, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SevenPCQuestionnaire(1, 1, 1, 1, 1, 1, 1);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SevenPCQuestionnaire(
                random_int(0, 1),
                random_int(0, 1),
                random_int(0, 1),
                random_int(0, 1),
                random_int(0, 1),
                random_int(0, 1),
                random_int(0, 1)
            );
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        for ($i = 0; $i < 7; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 7, 4);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 1;
                }
                new SevenPCQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('%s should be between 0 and 1', $questionsOrder[$i]), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }

        for ($i = 0; $i < 7; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 7, -1);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 1;
                }
                new SevenPCQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('%s should be between 0 and 1', $questionsOrder[$i]), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }
    }

    public function testToArray()
    {
        $questionnaire = new SevenPCQuestionnaire(0, 1, 0, 1, 0, 1, 0);
        $arr = $questionnaire->toArray();

        $this->assertCount(7, $arr['item']);

        $this->assertEquals(0, $arr['item']['irregularSize']);
        $this->assertEquals(1, $arr['item']['irregularPigmentation']);
        $this->assertEquals(0, $arr['item']['irregularBorder']);
        $this->assertEquals(1, $arr['item']['inflammation']);
        $this->assertEquals(0, $arr['item']['largerThanOtherLesions']);
        $this->assertEquals(1, $arr['item']['itchOrAltered']);
        $this->assertEquals(0, $arr['item']['crustedOrBleeding']);
    }

    public function testGetName()
    {
        $questionnaire = new SevenPCQuestionnaire(0, 1, 0, 1, 0, 1, 0);
        $this->assertEquals('sevenPc', $questionnaire::getName());
    }
}
