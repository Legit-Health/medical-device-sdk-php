<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\NsilQuestionnaire;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class NsilTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [[
            '/tests/resources/non_specific.jpg',
            new NsilQuestionnaire(),
            ["code" => null, "display" => null, "text" => "Non-specific finding"],
            [

                'item' => [
                    "dryness" => [
                        'value' => fn(float $value) => self::assertGreaterThanOrEqual(1, $value),
                        'interpretation' => null,
                        'text' => 'Dryness',
                        'additionalData' => [
                            'aiConfidence' => [
                                'code' => 'aiConfidence',
                                'text' => 'Confidence of the AI model on prediction',
                                'typeOfValue' => 'percentage'
                            ]
                        ]
                    ],
                    "erythema" => [
                        'value' => 0,
                        'interpretation' => null,
                        'text' => 'Erythema',
                        'additionalData' => [
                            'aiConfidence' => [
                                'code' => 'aiConfidence',
                                'text' => 'Confidence of the AI model on prediction',
                                'typeOfValue' => 'percentage'
                            ]
                        ]
                    ],
                    "inflammations" => [
                        'value' => 0,
                        'interpretation' => null,
                        'text' => 'Inflammations',
                        'additionalData' => [
                            'inflammatoryLesionCount' => [
                                'code' => 'inflammatoryLesionCount',
                                'text' => 'Number of inflammatory lesions',
                                'typeOfValue' => 'scalar'
                            ]
                        ]
                    ],
                ],
                'scoreValue' => fn(float $value) => self::assertGreaterThanOrEqual(1, $value),
                'interpretationCategory' => 'Mild',
                'intensity' => Intensity::Low,
                'attachment' => [
                    'annotation' => [
                        'title' => 'Annotated image with bounding boxes highlighting inflammatory lesions',
                        'height' => 1067,
                        'width' => 800
                    ]
                ]
            ]
        ]];
    }

    protected static function getSpecificMissingFields(): array
    {
        return [];
    }

    protected function buildValidArguments(string $currentDir): array
    {

        $body = [
            'bodySite' => 'armRight',
            'payload' => [
                'contentAttachment' => [
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/non_specific.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'EA89',
                        'display' => 'Generalised eczematous dermatitis of unspecified type',
                    ]],
                    'text' => 'Eczematous dermatitis',
                ]
            ],
            'scoringSystem'  => [
                NsilQuestionnaire::getName() => new NsilQuestionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
