<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\DlqiQuestionnaire;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\Intensity;

class DlqiTest extends AbstractSeverityAssessmentManualTest
{

    protected static function getRequestValues(): array
    {
        return [[
            '/tests/resources/nevus.jpg',
            new DlqiQuestionnaire(1, 2, 3, 1, 2, 3, 1, 2, 3, 1),
            ["code" => "2F20.Z", "display" => "Melanocytic naevus, unspecified", "text" => "Melanocytic naevus"],
            ['scoreValue' => 19, 'interpretationCategory' => 'Very large effect', 'intensity' => Intensity::High]
        ]];
    }

    protected static function getSpecificMissingFields(): array
    {
        return [
            'scoringSystem.dlqi.questionnaireResponse.item.question1 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question1',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question1']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question2 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question2',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question2']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question3 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question3',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question3']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question4 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question4',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question4']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question5 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question5',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question5']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question6 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question6',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question6']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question7 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question7',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question7']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question8 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question8',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question8']],
            ],

            'scoringSystem.dlqi.questionnaireResponse.item.question9 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question9',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question9']],
            ],
            'scoringSystem.dlqi.questionnaireResponse.item.question10 is required' => [
                'path' => 'scoringSystem.dlqi.questionnaireResponse.item.question10',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'dlqi', 'questionnaireResponse', 'item', 'question10']],
            ]
        ];
    }

    protected static function buildValidQuestionnaireResponse(): array
    {
        return [
            'questionnaireResponse' => new DlqiQuestionnaire(1, 2, 3, 1, 2, 3, 1, 2, 3, 1)->asArray()
        ];
    }

    protected static function questionnaireKey(): string
    {
        return DlqiQuestionnaire::getName();
    }
}
