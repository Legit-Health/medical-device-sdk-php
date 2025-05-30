<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ResvechQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class ResvechQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $questionsOrder = [
            'woundDimensions',
            'tissues',
            'edges',
            'tissueInWoundBed',
            'exudate',
            'frequencyOfPain',
            'macerationAroundWound',
            'tunneling',
            'increasingPain',
            'erythemaAroundWound',
            'edemaAroundWound',
            'temperatureRise',
            'increasingExudate',
            'purulentExudate',
            'tissueFriableOrBleedsEasily',
            'stationaryWound',
            'biofilmCompatibleTissue',
            'odor',
            'hypergranulation',
            'increasingWound',
            'satelliteLesions',
            'tissuePaleness'
        ];

        // Testing valid values (all within range)
        $exceptionIsThrown = false;
        try {
            new ResvechQuestionnaire(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ResvechQuestionnaire(4, 4, 4, 4, 4, 4, 1, 4, 4, 4, 4, 1, 4, 1, 4, 4, 4, 4, 4, 4, 4, 4);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        // Testing random valid values
        $exceptionIsThrown = false;
        try {
            new ResvechQuestionnaire(
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 1),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 1),
                random_int(0, 4),
                random_int(0, 1),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4),
                random_int(0, 4)
            );
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        // Testing invalid high values (out of range)
        for ($i = 0; $i < 21; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 22, 5);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = \in_array($j, [6, 11, 13], true) ? 1 : 3;
                }
                new ResvechQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('%s should be between 0 and %d', $questionsOrder[$i], \in_array($i, [6, 11, 13], true) ? 1 : 4), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }

        // Testing invalid negative values (out of range)
        for ($i = 0; $i < 21; $i++) {
            $exceptionIsThrown = false;
            try {
                $arr = array_fill(0, 22, -1);
                for ($j = 0; $j < $i; $j++) {
                    $arr[$j] = 1;
                }
                new ResvechQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('%s should be between 0 and %d', $questionsOrder[$i], \in_array($i, [6, 11, 13], true) ? 1 : 4), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }
    }
    public function testJsonSerialize()
    {
        $questionnaire = new ResvechQuestionnaire(
            0,
            1,
            2,
            3,
            4,
            0,
            1,
            2,
            3,
            4,
            0,
            1,
            2,
            1,
            4,
            0,
            1,
            2,
            3,
            4,
            0,
            1
        );
        $arr = $questionnaire->jsonSerialize();

        $this->assertCount(22, $arr['questionnaireResponse']['item']);

        $this->assertEquals(0, $arr['questionnaireResponse']['item']['woundDimensions']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['tissues']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['edges']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['tissueInWoundBed']);
        $this->assertEquals(4, $arr['questionnaireResponse']['item']['exudate']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['frequencyOfPain']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['macerationAroundWound']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['tunneling']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['increasingPain']);
        $this->assertEquals(4, $arr['questionnaireResponse']['item']['erythemaAroundWound']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['edemaAroundWound']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['temperatureRise']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['increasingExudate']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['purulentExudate']);
        $this->assertEquals(4, $arr['questionnaireResponse']['item']['tissueFriableOrBleedsEasily']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['stationaryWound']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['biofilmCompatibleTissue']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['odor']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['hypergranulation']);
        $this->assertEquals(4, $arr['questionnaireResponse']['item']['increasingWound']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['satelliteLesions']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['tissuePaleness']);
    }

    public function testGetName()
    {
        $questionnaire = new ResvechQuestionnaire(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->assertEquals('resvech', $questionnaire::getName());
    }
}
