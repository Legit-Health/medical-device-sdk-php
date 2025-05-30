<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\AgppgaQuestionnaire;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\Intensity;

class AgppgaTest extends AbstractSeverityAssessmentAutomaticLocalTest
{

    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/pustular_psoriasis.jpg',
                new AgppgaQuestionnaire(),
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
                BodySiteCode::HeadLeft
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
            'bodySite' => 'headLeft',
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
                AgppgaQuestionnaire::getName() => new AgppgaQuestionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
