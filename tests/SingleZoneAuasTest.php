<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\SingleZoneAuasQuestionnaire;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\Intensity;

class SingleZoneAuasTest extends AbstractSeverityAssessmentAutomaticLocalTest
{

    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/urticaria.jpg',
                new SingleZoneAuasQuestionnaire(3),
                ["code" => "EB05", "display" => "Urticaria of unspecified type", "text" => "Urticaria"],
                [
                    'item' => [
                        "wheals" => [
                            'value' => 2,
                            'interpretation' => "Moderate (20-50)",
                            'text' => 'Extent or number of wheals (hives) in the last 24 hours, scored from 0 (no wheals) to 3 (many or large confluent wheals).',
                            'additionalData' => [
                                'whealsCount' => [
                                    'code' => 'whealsCount',
                                    'text' => 'Number of hives/wheals',
                                    'typeOfValue' => 'scalar'
                                ]
                            ]
                        ],
                        "pruritus" => [
                            'value' => 3,
                            'interpretation' => "Severe (interferes with normal daily activity or sleep)",
                            'text' => 'Severity of itch in the last 24 hours, scored from 0 (no itch) to 3 (severe itch).'
                        ]
                    ],
                    'scoreValue' => fn(float $value) => self::assertEquals(5, $value),
                    'interpretationCategory' => 'Intense',
                    'intensity' => Intensity::High,
                    'attachment' => [
                        'annotation' => [
                            'title' => 'Annotated image with bounding boxes highlighting urticaria wheals',
                            'height' => 454,
                            'width' => 701
                        ]
                    ],
                    'detection' => true
                ],
                BodySiteCode::ArmLeft
            ]
        ];
    }

    protected static function getSpecificMissingFields(): array
    {
        return ['scoringSystem.auas.questionnaireResponse.item.pruritus is required' => [
            'path' => 'scoringSystem.auas.questionnaireResponse.item.pruritus',
            'expectedDetail' => ['loc' => ['body', 'scoringSystem', 'auas', 'questionnaireResponse', 'item', 'pruritus']],
        ],];
    }

    protected function buildValidArguments(string $currentDir): array
    {

        $body = [
            'bodySite' => 'armLeft',
            'payload' => [
                'contentAttachment' => [
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/urticaria.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'EB05',
                        'display' => 'Urticaria of unspecified type',
                    ]],
                    'text' => 'Urticaria',
                ]
            ],
            'scoringSystem'  => [
                SingleZoneAuasQuestionnaire::getName() => new SingleZoneAuasQuestionnaire(3)->jsonSerialize()
            ],
        ];

        return $body;
    }
}
