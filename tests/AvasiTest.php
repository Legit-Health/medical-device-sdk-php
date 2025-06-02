<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\AvasiQuestionnaire;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class AvasiTest extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [[
            '/tests/resources/vitiligo.jpg',
            new AvasiQuestionnaire(),
            ["code" => "ED63.0", "display" => "Vitiligo", "text" => "Vitiligo"],
            [
                'item' => null,
                'scoreValue' => fn(float $value) => self::assertGreaterThanOrEqual(40, $value),
                'interpretationCategory' => 'Moderate',
                'intensity' => Intensity::Moderate,
                'attachment' => [
                    'skinMaskRaw' => [
                        'title' => 'Skin mask raw',
                        'height' => 550,
                        'width' => 800
                    ],
                    'skinMaskBinary' => [
                        'title' => 'Skin mask binary',
                        'height' => 550,
                        'width' => 800
                    ],
                    'depigmentationMaskRaw' => [
                        'title' => 'Depigmentation mask raw',
                        'height' => 550,
                        'width' => 800
                    ],
                    'depigmentationMaskBinary' => [
                        'title' => 'Depigmentation mask binary',
                        'height' => 550,
                        'width' => 800
                    ],
                    'segmentation' => [
                        'title' => 'Segmented image',
                        'height' => 550,
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
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/vitiligo.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'ED63.0',
                        'display' => 'Vitiligo',
                    ]],
                    'text' => 'Vitiligo',
                ]
            ],
            'scoringSystem'  => [
                AvasiQuestionnaire::getName() => new AvasiQuestionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
