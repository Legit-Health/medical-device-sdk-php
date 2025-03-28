<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{
    ScoringSystems,
    SingleZoneApasiQuestionnaire,
    SingleZoneAuasQuestionnaire
};

use PHPUnit\Framework\TestCase;

class ScoringSystemsTest extends TestCase
{
    public function testToArray()
    {
        $auasQuestionnaire = new SingleZoneApasiQuestionnaire(3);
        $apasiQuestionnaire = new SingleZoneAuasQuestionnaire(3);
        $questionnaires = new ScoringSystems([$auasQuestionnaire, $apasiQuestionnaire]);

        $arr = $questionnaires->toArray();

        $this->assertCount(2, $arr);
        $this->assertTrue($arr['auas']['calculate']);
        $this->assertEquals(3, $arr['auas']['questionnaireResponse']['item']['pruritus']);
        $this->assertTrue($arr['apasi']['calculate']);
        $this->assertEquals(3, $arr['apasi']['questionnaireResponse']['item']['surface']);
    }
}
