<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\PgaQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class PgaQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        // Test valid inputs
        $exceptionIsThrown = false;
        try {
            new PgaQuestionnaire(0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PgaQuestionnaire(4, 4, 4);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PgaQuestionnaire(random_int(0, 4), random_int(0, 4), random_int(0, 4));
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        // Test out-of-range inputs
        for ($i = 0; $i < 3; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = [$i === 0 ? 5 : 0, $i === 1 ? 5 : 0, $i === 2 ? 5 : 0];
                new PgaQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $expectedMessage = sprintf(
                    '%s should be between %d and %d',
                    ['erythema', 'desquamation', 'induration'][$i],
                    0,
                    4
                );
                $this->assertEquals($expectedMessage, $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }
    }

    public function testToArray()
    {
        $pgaQuestionnaire = new PgaQuestionnaire(0, 1, 1);
        $arr = $pgaQuestionnaire->toArray();

        $this->assertCount(3, $arr['item']);

        $this->assertEquals(0, $arr['item']['erythema']);
        $this->assertEquals(1, $arr['item']['desquamation']);
        $this->assertEquals(1, $arr['item']['induration']);

        $pgaQuestionnaire = new PgaQuestionnaire(4, 2, 0);
        $arr = $pgaQuestionnaire->toArray();

        $this->assertCount(3, $arr['item']);

        $this->assertEquals(4, $arr['item']['erythema']);
        $this->assertEquals(2, $arr['item']['desquamation']);
        $this->assertEquals(0, $arr['item']['induration']);
    }

    public function testGetName()
    {
        $pgaQuestionnaire = new PgaQuestionnaire(0, 1, 0);
        $this->assertEquals('pga', $pgaQuestionnaire::getName());
    }
}
