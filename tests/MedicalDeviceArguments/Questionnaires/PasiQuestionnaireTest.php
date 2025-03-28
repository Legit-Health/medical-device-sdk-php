<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\PasiQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class PasiQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 0,
                'erythema' => 0,
                'induration' => 0,
                'desquamation' => 0,
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 6,
                'erythema' => 4,
                'induration' => 4,
                'desquamation' => 4
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => random_int(0, 6),
                'erythema' => random_int(0, 4),
                'induration' => random_int(0, 4),
                'desquamation' => random_int(0, 4)
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 7,
                'erythema' => 4,
                'induration' => 4,
                'desquamation' => 4
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('surface should be between 0 and 6', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => -1,
                'erythema' => 4,
                'induration' => 4,
                'desquamation' => 4
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('surface should be between 0 and 6', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 1,
                'erythema' => 5,
                'induration' => 4,
                'desquamation' => 4
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('erythema should be between 0 and 4', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 1,
                'erythema' => -5,
                'induration' => 4,
                'desquamation' => 4
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('erythema should be between 0 and 4', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 1,
                'erythema' => 4,
                'induration' => 5,
                'desquamation' => 4
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('induration should be between 0 and 4', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 1,
                'erythema' => 1,
                'induration' => -1,
                'desquamation' => 4
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('induration should be between 0 and 4', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 1,
                'erythema' => 4,
                'induration' => 4,
                'desquamation' => 5
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('desquamation should be between 0 and 4', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new PasiQuestionnaire(...[
                'surface' => 1,
                'erythema' => 1,
                'induration' => 4,
                'desquamation' => -1
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('desquamation should be between 0 and 4', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testToArray()
    {
        $pasiLocalQuestionnaire = new PasiQuestionnaire(5, 1, 2, 3);
        $arr = $pasiLocalQuestionnaire->toArray();

        $this->assertCount(4, $arr['item']);

        $this->assertEquals(5, $arr['item']['surface']);
        $this->assertEquals(1, $arr['item']['erythema']);
        $this->assertEquals(2, $arr['item']['induration']);
        $this->assertEquals(3, $arr['item']['desquamation']);
    }

    public function testGetName()
    {
        $manualPasiQuestionnaire = new PasiQuestionnaire(5, 1, 2, 3);
        $this->assertEquals('pasi', $manualPasiQuestionnaire::getName());
    }
}
