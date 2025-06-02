<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\Arguments\Params\Aihs4Questionnaire;
use LegitHealth\MedicalDevice\Arguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\Response\Value\Intensity;

class Aihs4Test extends AbstractSeverityAssessmentAutomaticLocalTest
{
    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/hidradenitis.jpg',
                new Aihs4Questionnaire(),
                ["code" => "ED92.0", "display" => "Hidradenitis suppurativa", "text" => "Hidradenitis suppurativa"],
                [
                    'item' => [
                        "abscess" => [
                            'value' => 0,
                            'interpretation' => null,
                            'text' => 'The number of abscesses identified during clinical examination.'
                        ],
                        "drainingTunnel" => [
                            'value' => 3,
                            'interpretation' => null,
                            'text' => 'The number of draining tunnels identified during clinical examination.'
                        ],
                        "nodule" => [
                            'value' => 8,
                            'interpretation' => null,
                            'text' => 'The number of nodules identified during clinical examination.'
                        ],
                    ],
                    'scoreValue' => fn(float $value) => self::assertGreaterThanOrEqual(20, $value),
                    'interpretationCategory' => 'Intense',
                    'intensity' => Intensity::High,
                    'attachment' => [
                        'annotation' => [
                            'title' => 'Annotated image with bounding boxes highlighting HS inflammatory lesions',
                            'height' => 800,
                            'width' => 548
                        ]
                    ],
                    'detection' => ['nodule' => 'Nodule', 'drainingTunnel' => 'Draining tunnel', 'abscess' => 'Abscess']
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
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/hidradenitis.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'ED92.0',
                        'display' => 'Hidradenitis suppurativa',
                    ]],
                    'text' => 'Hidradenitis suppurativa',
                ]
            ],
            'scoringSystem'  => [
                Aihs4Questionnaire::getName() => new Aihs4Questionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
