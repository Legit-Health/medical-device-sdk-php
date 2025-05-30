<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\Ihs4Questionnaire;
use PHPUnit\Framework\TestCase;

class Ihs4QuestionnaireTest extends TestCase
{
    public function testJsonSerialize()
    {
        $ihs4LocalQuestionnaire = new Ihs4Questionnaire(5, 4, 2);
        $arr = $ihs4LocalQuestionnaire->jsonSerialize();

        $this->assertCount(3, array_keys($arr['questionnaireResponse']['item']));
        $this->assertEquals(5, $arr['questionnaireResponse']['item']['nodule']);
        $this->assertEquals(4, $arr['questionnaireResponse']['item']['abscess']);
        $this->assertEquals(2, $arr['questionnaireResponse']['item']['drainingTunnel']);
    }

    public function testGetName()
    {
        $ihs4LocalQuestionnaire = new Ihs4Questionnaire(5, 4, 2,);
        $this->assertEquals('ihs4', $ihs4LocalQuestionnaire::getName());
    }
}
