<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\DlqiQuestionnaire;
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

    public function testJsonSerialize()
    {
        $dlqiQuestionnaire = new DlqiQuestionnaire(1, 2, 3, 1, 2, 3, 1, 2, 3, 1);
        $arr = $dlqiQuestionnaire->jsonSerialize();

        $this->assertCount(10, $arr['questionnaireResponse']['item']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['question1']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['question2']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['question3']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['question4']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['question5']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['question6']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['question7']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['question8']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['question9']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['question10']);
    }

    public function testGetName()
    {
        $dlqiQuestionnaire = new DlqiQuestionnaire(1, 2, 3, 1, 2, 3, 1, 2, 3, 3);
        $this->assertEquals('dlqi', $dlqiQuestionnaire::getName());
    }
}
