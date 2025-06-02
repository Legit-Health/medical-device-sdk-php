<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\Arguments\Params\{
    ScoringSystems,
    SingleZoneApasiQuestionnaire,
    SingleZoneAuasQuestionnaire
};

use PHPUnit\Framework\TestCase;

class ScoringSystemsTest extends TestCase
{
    public function testJsonSerialize()
    {
        $auasQuestionnaire = new SingleZoneApasiQuestionnaire(3);
        $apasiQuestionnaire = new SingleZoneAuasQuestionnaire(3);
        $questionnaires = new ScoringSystems([$auasQuestionnaire, $apasiQuestionnaire]);

        $arr = json_decode(json_encode($questionnaires), true);

        $this->assertCount(2, $arr);
        $this->assertEquals(3, $arr['auas']['questionnaireResponse']['item']['pruritus']);
        $this->assertEquals(3, $arr['apasi']['questionnaireResponse']['item']['surface']);
    }
}
