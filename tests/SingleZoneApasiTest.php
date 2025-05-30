<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\SingleZoneApasiQuestionnaire;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\Intensity;

class SingleZoneApasiTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/psoriasis_01.jpg',
                new SingleZoneApasiQuestionnaire(20),
                ["code" => "EA90", "display" => "Psoriasis", "text" => "Psoriasis"],
                [
                    'item' => [
                        "surface" => [
                            'value' => 2,
                            'text' => 'Surface area score'
                        ],
                        "desquamation" => [
                            'value' => 3,
                            'text' => 'Desquamation',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "erythema" => [
                            'value' => 3,
                            'text' => 'Erythema',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "induration" => [
                            'value' => 3,
                            'text' => 'Induration',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ]
                    ],
                    'scoreValue' => fn (float $value) => self::assertGreaterThan(15, $value),
                    'interpretationCategory' => 'Moderate',
                    'intensity' => Intensity::Moderate,
                    'globalScoreContribution' => fn (float $value) => self::assertLessThanOrEqual(10, $value),
                    'attachment' => [
                        'maskRaw' => [
                            'title' => 'Lesion mask raw',
                            'height' => 509,
                            'width' => 945
                        ],
                        'maskBinary' => [
                            'title' => 'Lesion mask binary',
                            'height' => 509,
                            'width' => 945
                        ],
                        'segmentation' => [
                            'title' => 'Segmentation',
                            'height' => 509,
                            'width' => 945
                        ]
                    ]
                ],
                BodySiteCode::ArmLeft
            ]
        ];
    }

    protected static function getSpecificMissingFields(): array
    {
        return [
            'scoringSystem.apasi.questionnaireResponse.item.surface is required' => [
                'path' => 'scoringSystem.apasi.questionnaireResponse.item.surface',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'apasi', 'questionnaireResponse', 'item', 'surface']],
            ]
        ];
    }

    protected function buildValidArguments(string $currentDir): array
    {

        $body = [
            'bodySite' => 'armLeft',
            'payload' => [
                'contentAttachment' => [
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/psoriasis_01.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'EA90',
                        'display' => 'Psoriasis',
                    ]],
                    'text' => 'Psoriasis',
                ]
            ],
            'scoringSystem'  => [
                SingleZoneApasiQuestionnaire::getName() => new SingleZoneApasiQuestionnaire(50)->jsonSerialize()
            ],
        ];

        return $body;
    }
}
