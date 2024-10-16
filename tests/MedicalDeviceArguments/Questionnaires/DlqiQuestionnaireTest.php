<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\DlqiQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class DlqiQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new DlqiQuestionnaire(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new DlqiQuestionnaire(3, 3, 3, 3, 3, 3, 3, 3, 3, 3);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new DlqiQuestionnaire(
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
                random_int(0, 3),
            );
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        for ($i = 0; $i < 10; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 10, 4);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 3;
                }
                new DlqiQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('question%d should be between 0 and 3', $i + 1), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }

        for ($i = 0; $i < 10; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 10, -1);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 3;
                }
                new DlqiQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('question%d should be between 0 and 3', $i + 1), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }
    }

    public function testToArray()
    {
        $dlqiQuestionnaire = new DlqiQuestionnaire(1, 2, 3, 1, 2, 3, 1, 2, 3, 1);
        $arr = $dlqiQuestionnaire->toArray();
        $this->assertCount(10, array_keys($arr['item']));
        $this->assertEquals('dlqi', $arr['questionnaire']);

        $this->assertEquals(1, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('question1', $arr['item'][0]['code']);

        $this->assertEquals(2, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('question2', $arr['item'][1]['code']);

        $this->assertEquals(3, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('question3', $arr['item'][2]['code']);

        $this->assertEquals(1, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals('question4', $arr['item'][3]['code']);

        $this->assertEquals(2, $arr['item'][4]['answer'][0]['value']);
        $this->assertEquals('question5', $arr['item'][4]['code']);

        $this->assertEquals(3, $arr['item'][5]['answer'][0]['value']);
        $this->assertEquals('question6', $arr['item'][5]['code']);

        $this->assertEquals(1, $arr['item'][6]['answer'][0]['value']);
        $this->assertEquals('question7', $arr['item'][6]['code']);

        $this->assertEquals(2, $arr['item'][7]['answer'][0]['value']);
        $this->assertEquals('question8', $arr['item'][7]['code']);

        $this->assertEquals(3, $arr['item'][8]['answer'][0]['value']);
        $this->assertEquals('question9', $arr['item'][8]['code']);

        $this->assertEquals(1, $arr['item'][9]['answer'][0]['value']);
        $this->assertEquals('question10', $arr['item'][9]['code']);
    }

    public function testGetName()
    {
        $dlqiQuestionnaire = new DlqiQuestionnaire(1, 2, 3, 1, 2, 3, 1, 2, 3, 3);
        $this->assertEquals('dlqi', $dlqiQuestionnaire::getName());
    }
}
