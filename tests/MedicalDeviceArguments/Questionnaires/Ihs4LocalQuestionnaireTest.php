<?php

namespace LegitHealth\MedicalDevice\Tests\MedicalDeviceArguments\Questionnaires;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\Ihs4LocalQuestionnaire;
use PHPUnit\Framework\TestCase;

class Ihs4LocalQuestionnaireTest extends TestCase
{
    public function testToArray()
    {
        $ihs4LocalQuestionnaire = new Ihs4LocalQuestionnaire(5, 4, 2);
        $arr = $ihs4LocalQuestionnaire->toArray();
        $this->assertCount(3, array_keys($arr['item']));
        $this->assertEquals('ihs4Local', $arr['questionnaire']);

        $this->assertEquals(5, $arr['item'][0]['answer'][0]['value']);
        $this->assertEquals('nodule', $arr['item'][0]['code']);

        $this->assertEquals(4, $arr['item'][1]['answer'][0]['value']);
        $this->assertEquals('abscess', $arr['item'][1]['code']);

        $this->assertEquals(2, $arr['item'][2]['answer'][0]['value']);
        $this->assertEquals('drainingTunnel', $arr['item'][2]['code']);
    }

    public function testGetName()
    {
        $ihs4LocalQuestionnaire = new Ihs4LocalQuestionnaire(5, 4, 2, );
        $this->assertEquals('ihs4Local', $ihs4LocalQuestionnaire::getName());
    }
}
