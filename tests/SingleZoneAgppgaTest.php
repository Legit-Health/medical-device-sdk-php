<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAgppgaQuestionnaire;
use LegitHealth\MedicalDevice\Arguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class SingleZoneAgppgaTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/pustular_psoriasis.jpg',
                new SingleZoneAgppgaQuestionnaire(),
                ["code" => "EA90.40", "display" => "Generalised pustular psoriasis", "text" => "Generalised pustular psoriasis"],
                [
                    'item' => [
                        "erythema" => [
                            'value' => 3,
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
                        "desquamation" => [
                            'value' => 2,
                            'interpretation' => null,
                            'text' => 'Desquamation',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "pustulation" => [
                            'value' => 3,
                            'interpretation' => null,
                            'text' => 'Pustulation',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                    ],
                    'scoreValue' => fn(float $value) => self::assertGreaterThanOrEqual(2, $value),
                    'interpretationCategory' => 'Moderate',
                    'intensity' => Intensity::Moderate,
                    'attachment' => null
                ],
                BodySiteCode::ArmLeft
            ]
        ];
    }

    protected static function getSpecificMissingFields(): array
    {
        return [];
    }

    protected function buildValidArguments(string $currentDir): array
    {

        $body = [
            'bodySite' => 'armLeft',
            'payload' => [
                'contentAttachment' => [
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/pustular_psoriasis.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'EA90.40',
                        'display' => 'Generalised pustular psoriasis',
                    ]],
                    'text' => 'Generalised pustular psoriasis',
                ]
            ],
            'scoringSystem'  => [
                SingleZoneAgppgaQuestionnaire::getName() => new SingleZoneAgppgaQuestionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
