<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\Pure4Questionnaire;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class Pure4Test extends AbstractSeverityAssessmentManualTest
{
    protected static function getRequestValues(): array
    {
        return [[
            '/tests/resources/psoriasis_01.jpg',
            new Pure4Questionnaire(1, 0, 1, 0),
            ["code" => "EA90", "display" => "Psoriasis", "text" => "Psoriasis"],
            ['scoreValue' => 2, 'interpretationCategory' => 'Positive', 'intensity' => Intensity::High]
        ]];
    }

    protected static function getSpecificMissingFields(): array
    {
        return [
            'scoringSystem.pure4.questionnaireResponse.item.question1 is required' => [
                'path' => 'scoringSystem.pure4.questionnaireResponse.item.question1',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'pure4', 'questionnaireResponse', 'item', 'question1']],
            ],
            'scoringSystem.pure4.questionnaireResponse.item.question2 is required' => [
                'path' => 'scoringSystem.pure4.questionnaireResponse.item.question2',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'pure4', 'questionnaireResponse', 'item', 'question2']],
            ],
            'scoringSystem.pure4.questionnaireResponse.item.question3 is required' => [
                'path' => 'scoringSystem.pure4.questionnaireResponse.item.question3',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'pure4', 'questionnaireResponse', 'item', 'question3']],
            ],
            'scoringSystem.pure4.questionnaireResponse.item.question4 is required' => [
                'path' => 'scoringSystem.pure4.questionnaireResponse.item.question4',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'pure4', 'questionnaireResponse', 'item', 'question4']],
            ]
        ];
    }

    protected static function buildValidQuestionnaireResponse(): mixed
    {
        return new Pure4Questionnaire(1, 0, 1, 0)->jsonSerialize();
    }

    protected static function questionnaireKey(): string
    {
        return Pure4Questionnaire::getName();
    }
}
