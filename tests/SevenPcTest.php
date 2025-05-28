<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\SevenPcQuestionnaire;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\Intensity;

class SevenPcTest extends AbstractSeverityAssessmentManualTest
{

    protected static function getRequestValues(): array
    {
        return [[
            '/tests/resources/nevus.jpg',
            new SevenPcQuestionnaire(1, 0, 0, 1, 0, 0, 1),
            ["code" => "2F20.Z", "display" => "Melanocytic naevus, unspecified", "text" => "Melanocytic naevus"],
            ['scoreValue' => 6, 'interpretationCategory' => 'High risk', 'intensity' => Intensity::High]
        ]];
    }

    protected static function getSpecificMissingFields(): array
    {
        return [
            'scoringSystem.sevenPc.questionnaireResponse.item.changeInSize is required' => [
                'path' => 'scoringSystem.sevenPc.questionnaireResponse.item.changeInSize',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'sevenPc', 'questionnaireResponse', 'item', 'changeInSize']],
            ],
            'scoringSystem.sevenPc.questionnaireResponse.item.irregularPigmentation is required' => [
                'path' => 'scoringSystem.sevenPc.questionnaireResponse.item.irregularPigmentation',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'sevenPc', 'questionnaireResponse', 'item', 'irregularPigmentation']],
            ],
            'scoringSystem.sevenPc.questionnaireResponse.item.irregularBorder is required' => [
                'path' => 'scoringSystem.sevenPc.questionnaireResponse.item.irregularBorder',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'sevenPc', 'questionnaireResponse', 'item', 'irregularBorder']],
            ],
            'scoringSystem.sevenPc.questionnaireResponse.item.inflammation is required' => [
                'path' => 'scoringSystem.sevenPc.questionnaireResponse.item.inflammation',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'sevenPc', 'questionnaireResponse', 'item', 'inflammation']],
            ],
            'scoringSystem.sevenPc.questionnaireResponse.item.itchOrAlteredSensation is required' => [
                'path' => 'scoringSystem.sevenPc.questionnaireResponse.item.itchOrAlteredSensation',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'sevenPc', 'questionnaireResponse', 'item', 'itchOrAlteredSensation']],
            ],
            'scoringSystem.sevenPc.questionnaireResponse.item.largerThanOtherLesions is required' => [
                'path' => 'scoringSystem.sevenPc.questionnaireResponse.item.largerThanOtherLesions',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'sevenPc', 'questionnaireResponse', 'item', 'largerThanOtherLesions']],
            ]
        ];
    }

    protected static function buildValidQuestionnaireResponse(): array
    {
        return [
            'questionnaireResponse' => new SevenPcQuestionnaire(1, 0, 0, 1, 0, 0, 1)->asArray()
        ];
    }

    protected static function questionnaireKey(): string
    {
        return SevenPcQuestionnaire::getName();
    }
}
