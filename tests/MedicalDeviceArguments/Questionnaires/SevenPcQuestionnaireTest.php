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
        $this->assertCount(7, array_keys($arr['item']));
        $this->assertEquals('7Pc', $arr['questionnaire']);

        $this->assertEquals(0, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('irregularSize', $arr['item'][0]['code']);

        $this->assertEquals(1, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('irregularPigmentation', $arr['item'][1]['code']);

        $this->assertEquals(0, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('irregularBorder', $arr['item'][2]['code']);

        $this->assertEquals(1, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals('inflammation', $arr['item'][3]['code']);

        $this->assertEquals(0, $arr['item'][4]['answer'][0]['value']);
        $this->assertEquals('largerThanOtherLesions', $arr['item'][4]['code']);

        $this->assertEquals(1, $arr['item'][5]['answer'][0]['value']);
        $this->assertEquals('itchOrAltered', $arr['item'][5]['code']);

        $this->assertEquals(0, $arr['item'][6]['answer'][0]['value']);
        $this->assertEquals('crustedOrBleeding', $arr['item'][6]['code']);
    }

    public function testGetName()
    {
        $questionnaire = new SevenPCQuestionnaire(0, 1, 0, 1, 0, 1, 0);
        $this->assertEquals('7Pc', $questionnaire::getName());
    }
}
