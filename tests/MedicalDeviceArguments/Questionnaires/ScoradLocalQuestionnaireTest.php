<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\ScoradLocalQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class ScoradLocalQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 0,
                'erythema' => 0,
                'swelling' => 0,
                'crusting' => 0,
                'excoriation' => 0,
                'lichenification' => 0,
                'dryness' => 0,
                'itchiness' => 0,
                'sleeplessness' => 0
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 100,
                'erythema' => 3,
                'swelling' => 3,
                'crusting' => 3,
                'excoriation' => 3,
                'lichenification' => 3,
                'dryness' => 3,
                'itchiness' => 10,
                'sleeplessness' => 10
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => random_int(0, 100),
                'erythema' => random_int(0, 3),
                'swelling' => random_int(0, 3),
                'crusting' => random_int(0, 3),
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 101,
                'erythema' => random_int(0, 3),
                'swelling' => random_int(0, 3),
                'crusting' => random_int(0, 3),
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('surface should be between 0 and 100', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => -1,
                'erythema' => random_int(0, 3),
                'swelling' => random_int(0, 3),
                'crusting' => random_int(0, 3),
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('surface should be between 0 and 100', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 4,
                'swelling' => random_int(0, 3),
                'crusting' => random_int(0, 3),
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('erythema should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => -1,
                'swelling' => random_int(0, 3),
                'crusting' => random_int(0, 3),
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('erythema should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 4,
                'crusting' => random_int(0, 3),
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('swelling should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => -1,
                'crusting' => random_int(0, 3),
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('swelling should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 4,
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('crusting should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => -1,
                'excoriation' => random_int(0, 3),
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('crusting should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 4,
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('excoriation should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => -1,
                'lichenification' => random_int(0, 3),
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('excoriation should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => 4,
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('lichenification should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => -1,
                'dryness' => random_int(0, 3),
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('lichenification should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => 3,
                'dryness' => 4,
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('dryness should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => 3,
                'dryness' => -1,
                'itchiness' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('dryness should be between 0 and 3', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => 3,
                'dryness' => 1,
                'itchiness' => 11,
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('itchiness should be between 0 and 10', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => 3,
                'dryness' => 1,
                'itchiness' => -1,
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('itchiness should be between 0 and 10', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => 3,
                'dryness' => 1,
                'itchiness' => 1,
                'sleeplessness' => 11
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('sleeplessness should be between 0 and 10', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new ScoradLocalQuestionnaire(...[
                'surface' => 50,
                'erythema' => 1,
                'swelling' => 1,
                'crusting' => 2,
                'excoriation' => 2,
                'lichenification' => 3,
                'dryness' => 1,
                'itchiness' => 1,
                'sleeplessness' => -1
            ]);
        } catch (Throwable $e) {
            $exceptionIsThrown = true;
            $this->assertEquals('sleeplessness should be between 0 and 10', $e->getMessage());
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testToArray()
    {
        $scoradLocalQuestionnaire = new ScoradLocalQuestionnaire(25, 0, 1, 2, 3, 0, 1, 5, 6);
        $arr = $scoradLocalQuestionnaire->toArray();
        $this->assertCount(9, array_keys($arr['item']));
        $this->assertEquals('scoradLocal', $arr['questionnaire']);

        $this->assertEquals(25, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('surface', $arr['item'][0]['code']);

        $this->assertEquals(0, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('erythema', $arr['item'][1]['code']);

        $this->assertEquals(1, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('swelling', $arr['item'][2]['code']);

        $this->assertEquals(2, $arr['item'][3]['answer'][0]['value']);
        $this->assertEquals('crusting', $arr['item'][3]['code']);

        $this->assertEquals(3, $arr['item'][4]['answer'][0]['value']);
        $this->assertEquals('excoriation', $arr['item'][4]['code']);

        $this->assertEquals(0, $arr['item'][5]['answer'][0]['value']);
        $this->assertEquals('lichenification', $arr['item'][5]['code']);

        $this->assertEquals(1, $arr['item'][6]['answer'][0]['value']);
        $this->assertEquals('dryness', $arr['item'][6]['code']);

        $this->assertEquals(5, $arr['item'][7]['answer'][0]['value']);
        $this->assertEquals('itchiness', $arr['item'][7]['code']);

        $this->assertEquals(6, $arr['item'][8]['answer'][0]['value']);
        $this->assertEquals('sleeplessness', $arr['item'][8]['code']);
    }

    public function testGetName()
    {
        $scoradLocalQuestionnaire = new ScoradLocalQuestionnaire(25, 3, 3, 3, 2, 1, 3, 5, 10);
        $this->assertEquals('scoradLocal', $scoradLocalQuestionnaire::getName());
    }
}
