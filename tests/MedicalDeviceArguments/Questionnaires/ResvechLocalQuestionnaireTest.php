<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ResvechLocalQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class ResvechLocalQuestionnaireTest extends TestCase
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
            new ResvechLocalQuestionnaire(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ResvechLocalQuestionnaire(4, 4, 4, 4, 4, 4, 1, 4, 4, 4, 4, 1, 4, 1, 4, 4, 4, 4, 4, 4, 4, 4);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        // Testing random valid values
        $exceptionIsThrown = false;
        try {
            new ResvechLocalQuestionnaire(
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
                new ResvechLocalQuestionnaire(...$arr);
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
                new ResvechLocalQuestionnaire(...$arr);
            } catch (Throwable $e) {
                $exceptionIsThrown = true;
                $this->assertEquals(sprintf('%s should be between 0 and %d', $questionsOrder[$i], \in_array($i, [6, 11, 13], true) ? 1 : 4), $e->getMessage());
            }
            $this->assertTrue($exceptionIsThrown);
        }
    }

    public function testToArray()
    {
        $questionnaire = new ResvechLocalQuestionnaire(
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
        $arr = $questionnaire->toArray();
        $this->assertCount(22, array_keys($arr['item']));
        $this->assertEquals('resvechLocal', $arr['questionnaire']);

        $this->assertEquals(0, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('woundDimensions', $arr['item'][0]['code']);

        $this->assertEquals(1, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('tissues', $arr['item'][1]['code']);

        $this->assertEquals(2, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('edges', $arr['item'][2]['code']);

        $this->assertEquals(3, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals('tissueInWoundBed', $arr['item'][3]['code']);

        $this->assertEquals(4, $arr['item'][4]['answer'][0]['value']);
        $this->assertEquals('exudate', $arr['item'][4]['code']);

        $this->assertEquals(0, $arr['item'][5]['answer'][0]['value']);
        $this->assertEquals('frequencyOfPain', $arr['item'][5]['code']);

        $this->assertEquals(1, $arr['item'][6]['answer'][0]['value']);
        $this->assertEquals('macerationAroundWound', $arr['item'][6]['code']);

        $this->assertEquals(2, $arr['item'][7]['answer'][0]['value']);
        $this->assertEquals('tunneling', $arr['item'][7]['code']);

        $this->assertEquals(3, $arr['item'][8]['answer'][0]['value']);
        $this->assertEquals('increasingPain', $arr['item'][8]['code']);

        $this->assertEquals(4, $arr['item'][9]['answer'][0]['value']);
        $this->assertEquals('erythemaAroundWound', $arr['item'][9]['code']);

        $this->assertEquals(0, $arr['item'][10]['answer'][0]['value']);
        $this->assertEquals('edemaAroundWound', $arr['item'][10]['code']);

        $this->assertEquals(1, $arr['item'][11]['answer'][0]['value']);
        $this->assertEquals('temperatureRise', $arr['item'][11]['code']);

        $this->assertEquals(2, $arr['item'][12]['answer'][0]['value']);
        $this->assertEquals('increasingExudate', $arr['item'][12]['code']);

        $this->assertEquals(1, $arr['item'][13]['answer'][0]['value']);
        $this->assertEquals('purulentExudate', $arr['item'][13]['code']);

        $this->assertEquals(4, $arr['item'][14]['answer'][0]['value']);
        $this->assertEquals('tissueFriableOrBleedsEasily', $arr['item'][14]['code']);

        $this->assertEquals(0, $arr['item'][15]['answer'][0]['value']);
        $this->assertEquals('stationaryWound', $arr['item'][15]['code']);

        $this->assertEquals(1, $arr['item'][16]['answer'][0]['value']);
        $this->assertEquals('biofilmCompatibleTissue', $arr['item'][16]['code']);

        $this->assertEquals(2, $arr['item'][17]['answer'][0]['value']);
        $this->assertEquals('odor', $arr['item'][17]['code']);

        $this->assertEquals(3, $arr['item'][18]['answer'][0]['value']);
        $this->assertEquals('hypergranulation', $arr['item'][18]['code']);

        $this->assertEquals(4, $arr['item'][19]['answer'][0]['value']);
        $this->assertEquals('increasingWound', $arr['item'][19]['code']);

        $this->assertEquals(0, $arr['item'][20]['answer'][0]['value']);
        $this->assertEquals('satelliteLesions', $arr['item'][20]['code']);

        $this->assertEquals(1, $arr['item'][21]['answer'][0]['value']);
        $this->assertEquals('tissuePaleness', $arr['item'][21]['code']);
    }

    public function testGetName()
    {
        $questionnaire = new ResvechLocalQuestionnaire(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->assertEquals('resvechLocal', $questionnaire::getName());
    }
}
