<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\ScoradQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class ScoradQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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
            new ScoradQuestionnaire(...[
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

    public function testJsonSerialize()
    {
        $scoradLocalQuestionnaire = new ScoradQuestionnaire(25, 0, 1, 2, 3, 0, 1, 5, 6);
        $arr = $scoradLocalQuestionnaire->jsonSerialize();

        $this->assertCount(9, $arr['questionnaireResponse']['item']);

        $this->assertEquals(25, $arr['questionnaireResponse']['item']['surface']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['erythema']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['swelling']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['crusting']);
        $this->assertEquals(3, $arr['questionnaireResponse']['item']['excoriation']);
        $this->assertEquals(0, $arr['questionnaireResponse']['item']['lichenification']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['dryness']);
        $this->assertEquals(5, $arr['questionnaireResponse']['item']['itchiness']);
        $this->assertEquals(6, $arr['questionnaireResponse']['item']['sleeplessness']);
    }

    public function testGetName()
    {
        $scoradLocalQuestionnaire = new ScoradQuestionnaire(25, 3, 3, 3, 2, 1, 3, 5, 10);
        $this->assertEquals('scorad', $scoradLocalQuestionnaire::getName());
    }
}
