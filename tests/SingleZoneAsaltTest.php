<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAsaltQuestionnaire;
use LegitHealth\MedicalDevice\Arguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class SingleZoneAsaltTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/alopecia.jpg',
                new SingleZoneAsaltQuestionnaire(),
                ["code" => "ED70", "display" => "Alopecia or hair loss", "text" => "Alopecia"],
                [
                    'item' => null,
                    'scoreValue' => fn(float $value) => self::assertGreaterThanOrEqual(7, $value),
                    'interpretationCategory' => 'Moderate',
                    'intensity' => Intensity::Moderate,
                    'globalScoreContribution' => fn(float $value) => self::assertLessThanOrEqual(10, $value),
                    'attachment' => [
                        'hairMaskRaw' => [
                            'title' => 'Hair mask raw',
                            'height' => 563,
                            'width' => 1000
                        ],
                        'hairMaskBinary' => [
                            'title' => 'Hair mask binary',
                            'height' => 563,
                            'width' => 1000
                        ],
                        'baldnessMaskRaw' => [
                            'title' => 'Baldness mask raw',
                            'height' => 563,
                            'width' => 1000
                        ],
                        'baldnessMaskBinary' => [
                            'title' => 'Baldness mask binary',
                            'height' => 563,
                            'width' => 1000
                        ],
                        'segmentation' => [
                            'title' => 'Segmentation',
                            'height' => 563,
                            'width' => 1000
                        ]
                    ]
                ],
                BodySiteCode::HeadLeft
            ],
            [
                '/tests/resources/alopecia.jpg',
                new SingleZoneAsaltQuestionnaire(),
                ["code" => "ED70", "display" => "Alopecia or hair loss", "text" => "Alopecia"],
                null,
                BodySiteCode::ArmLeft,
                422
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
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/alopecia.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'ED70',
                        'display' => 'Alopecia or hair loss',
                    ]],
                    'text' => 'Alopecia',
                ]
            ],
            'scoringSystem'  => [
                SingleZoneAsaltQuestionnaire::getName() => new SingleZoneAsaltQuestionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
