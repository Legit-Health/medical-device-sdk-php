<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{
    ApasiLocalQuestionnaire,
    AuasLocalQuestionnaire,
    Questionnaires
};

use PHPUnit\Framework\TestCase;

class QuestionnairesTest extends TestCase
{
    public function testToArray()
    {
        $auasQuestionnaire = new AuasLocalQuestionnaire(3);
        $apasiQuestionnaire = new ApasiLocalQuestionnaire(4);
        $questionnaires = new Questionnaires([$auasQuestionnaire, $apasiQuestionnaire]);

        $arr = $questionnaires->toArray();

        $this->assertCount(2, $arr);
        $this->assertEquals('auasLocal', $arr[0]['questionnaire']);
        $this->assertEquals('itchiness', $arr[0]['item'][0]['code']);
        $this->assertEquals('apasiLocal', $arr[1]['questionnaire']);
        $this->assertEquals('surface', $arr[1]['item'][0]['code']);
    }
}
