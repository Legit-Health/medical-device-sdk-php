<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAeasiQuestionnaire;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class SingleZoneAeasiTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/dermatitis.jpg',
                new SingleZoneAeasiQuestionnaire(60, 40),
                ["code" => "EA89", "display" => "Generalised eczematous dermatitis of unspecified type", "text" => "Eczematous dermatitis"],
                [
                    'item' => [
                        "surface" => [
                            'value' => 4,
                            'text' => 'Surface area score'
                        ],
                        "redness" => [
                            'value' => 2,
                            'text' => 'Redness',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "thickness" => [
                            'value' => 2,
                            'text' => 'Thickness',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "scratching" => [
                            'value' => 0,
                            'text' => 'Scratching',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "lichenification" => [
                            'value' => 0,
                            'text' => 'Lichenification',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ]
                    ],
                    'scoreValue' => fn(float $value) => self::assertGreaterThan(15, $value),
                    'interpretationCategory' => 'Moderate',
                    'intensity' => Intensity::Moderate,
                    'globalScoreContribution' => fn(float $value) => self::assertGreaterThanOrEqual(3, $value),
                    'attachment' => [
                        'maskRaw' => [
                            'title' => 'Eczema mask raw',
                            'height' => 350,
                            'width' => 650
                        ],
                        'maskBinary' => [
                            'title' => 'Eczema mask binary',
                            'height' => 350,
                            'width' => 650
                        ],
                        'segmentation' => [
                            'title' => 'Segmentation',
                            'height' => 350,
                            'width' => 650
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
            'scoringSystem.aeasi.questionnaireResponse.item.surface is required' => [
                'path' => 'scoringSystem.aeasi.questionnaireResponse.item.surface',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'aeasi', 'questionnaireResponse', 'item', 'surface']],
            ],
            'scoringSystem.aeasi.questionnaireResponse.item.patientAge is required' => [
                'path' => 'scoringSystem.aeasi.questionnaireResponse.item.patientAge',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'aeasi', 'questionnaireResponse', 'item', 'patientAge']],
            ],
        ];
    }

    protected function buildValidArguments(string $currentDir): array
    {

        $body = [
            'bodySite' => 'armLeft',
            'payload' => [
                'contentAttachment' => [
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/dermatitis.jpg')),
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
                SingleZoneAeasiQuestionnaire::getName() => new SingleZoneAeasiQuestionnaire(40, 30)->jsonSerialize()
            ],
        ];

        return $body;
    }
}
