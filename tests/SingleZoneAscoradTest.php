<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAscoradQuestionnaire;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class SingleZoneAscoradTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/dermatitis.jpg',
                new SingleZoneAscoradQuestionnaire(60, 5, 7),
                ["code" => "EA89", "display" => "Generalised eczematous dermatitis of unspecified type", "text" => "Eczematous dermatitis"],
                [
                    'item' => [
                        "surface" => [
                            'value' => 60,
                            'text' => 'Percentage of the body zone\'s surface area affected by the lesion.',
                            'additionalData' => [
                                'surfaceAreaOverBsa' => [
                                    'code' => 'surfaceAreaOverBsa',
                                    'text' => 'Affected surface area as a percentage of the whole body',
                                    'typeOfValue' => 'scalar'
                                ]
                            ]
                        ],
                        "crusting" => [
                            'value' => 2,
                            'text' => 'Crusting',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "dryness" => [
                            'value' => 1,
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
                            'value' => 2,
                            'text' => 'Erythema',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "excoriation" => [
                            'value' => 0,
                            'text' => 'Excoriation',
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
                        ],
                        "swelling" => [
                            'value' => 2,
                            'text' => 'Swelling',
                            'additionalData' => [
                                'aiConfidence' => [
                                    'code' => 'aiConfidence',
                                    'text' => 'Confidence of the AI model on prediction',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "pruritus" => [
                            'value' => 5,
                            'text' => 'Pruritus'
                        ],
                        "sleeplessness" => [
                            'value' => 7,
                            'text' => 'Sleeplessness'
                        ]
                    ],
                    'scoreValue' => fn(float $value) => self::assertGreaterThan(37, $value),
                    'interpretationCategory' => 'Moderate',
                    'intensity' => Intensity::Moderate,
                    'attachment' => [
                        'maskRaw' => [
                            'title' => 'Lesion mask raw',
                            'height' => 350,
                            'width' => 650
                        ],
                        'maskBinary' => [
                            'title' => 'Lesion mask binary',
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
            'scoringSystem.ascorad.questionnaireResponse.item.surface is required' => [
                'path' => 'scoringSystem.ascorad.questionnaireResponse.item.surface',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'ascorad', 'questionnaireResponse', 'item', 'surface']],
            ],
            'scoringSystem.ascorad.questionnaireResponse.item.pruritus is required' => [
                'path' => 'scoringSystem.ascorad.questionnaireResponse.item.pruritus',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'ascorad', 'questionnaireResponse', 'item', 'pruritus']],
            ],
            'scoringSystem.ascorad.questionnaireResponse.item.sleeplessness is required' => [
                'path' => 'scoringSystem.ascorad.questionnaireResponse.item.sleeplessness',
                'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'ascorad', 'questionnaireResponse', 'item', 'sleeplessness']],
            ]
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
                SingleZoneAscoradQuestionnaire::getName() => new SingleZoneAscoradQuestionnaire(50, 7, 3)->jsonSerialize()
            ],
        ];

        return $body;
    }
}
