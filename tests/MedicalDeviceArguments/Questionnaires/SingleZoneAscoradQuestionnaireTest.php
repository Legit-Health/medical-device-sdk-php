<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAscoradQuestionnaire;
use PHPUnit\Framework\TestCase;
use Throwable;

class SingleZoneAscoradQuestionnaireTest extends TestCase
{
    public function testValidate()
    {
        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => 0,
                'pruritus' => 0,
                'sleeplessness' => 0
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => 100,
                'pruritus' => 10,
                'sleeplessness' => 10
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => random_int(0, 100),
                'pruritus' => random_int(0, 10),
                'sleeplessness' => random_int(0, 10)
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertFalse($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => 101,
                'pruritus' => 11,
                'sleeplessness' => 11
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => 100,
                'pruritus' => 11,
                'sleeplessness' => 11
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => 100,
                'pruritus' => 10,
                'sleeplessness' => 11
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => -1,
                'pruritus' => -1,
                'sleeplessness' => -1
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => 0,
                'pruritus' => -1,
                'sleeplessness' => -1
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);

        $exceptionIsThrown = false;
        try {
            new SingleZoneAscoradQuestionnaire(...[
                'surface' => 0,
                'pruritus' => 0,
                'sleeplessness' => -1
            ]);
        } catch (Throwable) {
            $exceptionIsThrown = true;
        }
        $this->assertTrue($exceptionIsThrown);
    }

    public function testJsonSerialize()
    {
        $singleZoneAscoradQuestionnaire = new SingleZoneAscoradQuestionnaire(27, 2, 1);
        $arr = $singleZoneAscoradQuestionnaire->jsonSerialize();
        $this->assertCount(3, array_keys($arr['questionnaireResponse']['item']));
        $this->assertEquals(27, $arr['questionnaireResponse']['item']['surface']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['pruritus']);
        $this->assertEquals(1, $arr['questionnaireResponse']['item']['sleeplessness']);
    }

    public function testGetName()
    {
        $singleZoneAscoradQuestionnaire = new SingleZoneAscoradQuestionnaire(27, 2, 2);
        $this->assertEquals('ascorad', $singleZoneAscoradQuestionnaire::getName());
    }
}
