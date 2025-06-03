<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\SingleZoneAladinQuestionnaire;
use LegitHealth\MedicalDevice\Arguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class SingleZoneAladinTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/acne.jpg',
                new SingleZoneAladinQuestionnaire(),
                ["code" => "ED80.Z", "display" => "Acne", "text" => "Acne"],
                [
                    'item' => [
                        "acne" => [
                            'value' => 33,
                            'interpretation' => 'Moderate',
                            'text' => 'Number of acne lesions',
                            'additionalData' => [
                                'acneDensity' => [
                                    'code' => 'acneDensity',
                                    'text' => 'Acne density score',
                                    'typeOfValue' => 'percentage',
                                    "interpretation" => "Very low"
                                ]
                            ]
                        ]
                    ],
                    'scoreValue' => fn(float $value) => self::assertGreaterThanOrEqual(30, $value),
                    'interpretationCategory' => 'Grade 4',
                    'intensity' => Intensity::Moderate,
                    'attachment' => [
                        'annotation' => [
                            'title' => 'Annotated image with bounding boxes highlighting acne pimples',
                            'height' => 400,
                            'width' => 500
                        ]
                    ],
                    'detection' => ['acne' => 'Acne']
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
            'bodySite' => 'headFront',
            'payload' => [
                'contentAttachment' => [
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/acne.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'ED80.Z',
                        'display' => 'Acne',
                    ]],
                    'text' => 'Acne',
                ]
            ],
            'scoringSystem'  => [
                SingleZoneAladinQuestionnaire::getName() => new SingleZoneAladinQuestionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
