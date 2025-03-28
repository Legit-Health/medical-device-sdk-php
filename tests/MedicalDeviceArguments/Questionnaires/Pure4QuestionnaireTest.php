<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\Pure4Questionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class Pure4QuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new Pure4Questionnaire(...[
                'question1' => 0,
                'question2' => 0,
                'question3' => 0,
                'question4' => 0,
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new Pure4Questionnaire(...[
                'question1' => 1,
                'question2' => 1,
                'question3' => 1,
                'question4' => 1,
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new Pure4Questionnaire(...[
                'question1' => random_int(0, 1),
                'question2' => random_int(0, 1),
                'question3' => random_int(0, 1),
                'question4' => random_int(0, 1)
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        for ($i = 0; $i < 4; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 4, 2);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 0;
                }
                new Pure4Questionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('question%d should be between 0 and 1', $i + 1), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }

        for ($i = 0; $i < 4; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 4, -1);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 1;
                }
                new Pure4Questionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('question%d should be between 0 and 1', $i + 1), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }
    }

    public function testToArray()
    {
        $pure4Questionnaire = new Pure4Questionnaire(0, 1, 0, 1);
        $arr = $pure4Questionnaire->toArray();

        $this->assertCount(4, $arr['item']);

        $this->assertEquals(0, $arr['item']['question1']);
        $this->assertEquals(1, $arr['item']['question2']);
        $this->assertEquals(0, $arr['item']['question3']);
        $this->assertEquals(1, $arr['item']['question4']);

        $pure4Questionnaire = new Pure4Questionnaire(...[
            'question1' => 1,
            'question2' => 0,
            'question3' => 1,
            'question4' => 0,
        ]);
        $arr = $pure4Questionnaire->toArray();

        $this->assertCount(4, $arr['item']);

        $this->assertEquals(1, $arr['item']['question1']);
        $this->assertEquals(0, $arr['item']['question2']);
        $this->assertEquals(1, $arr['item']['question3']);
        $this->assertEquals(0, $arr['item']['question4']);
    }


    public function testGetName()
    {
        $pure4Questionnaire = new Pure4Questionnaire(0, 1, 0, 0);
        $this->assertEquals('pure4', $pure4Questionnaire::getName());
    }
}
