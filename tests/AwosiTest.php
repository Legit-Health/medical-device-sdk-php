<?php

namespace LegitHealth\MedicalDevice\Tests;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\AwosiQuestionnaire;
use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\BodySiteCode;
use LegitHealth\MedicalDevice\MedicalDeviceResponse\Value\Intensity;

class AwosiTest extends AbstractSeverityAssessmentAutomaticLocalTest
{

    protected static function getRequestValues(): array
    {
        return [
            [
                '/tests/resources/ulcera.jpg',
                new AwosiQuestionnaire(),
                ["code" => "EH90.Z", "display" => "Pressure ulcer of unspecified grade", "text" => "Pressure ulcer"],
                [
                    'item' => [
                        "erythema" => [
                            'value' => 0,
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
                        "bordersDamaged" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => 'Borders.Damaged',
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "bordersDelimited" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => 'Borders.Delimited',
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "bordersDiffused" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => 'Borders.Diffused',
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "bordersThickened" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => 'Borders.Thickened',
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "bordersIndistinguishable" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => 'Borders.Indistinguishable',
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "perilesionalErythema" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Perilesional erythema",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "perilesionalMaceration" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Perilesional maceration",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "biofilmCompatibleTissue" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Biofilm-compatible tissue",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "affectedTissueBoneAndOrAdjacent" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Affected tissues.Bone and/or adjacent tissues",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "affectedTissueDermisEpidermis" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Affected tissues.Dermis-epidermis",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "affectedTissueMuscle" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Affected tissues.Muscle",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "affectedTissueSubcutaneous" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Affected tissues.Subcutaneous tissue",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "affectedTissueHealedSkin" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Affected tissues.Intact scarred skin",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "exudationFibrinous" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Type of exudation.Fibrinous",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "exudationPurulent" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Type of exudation.Purulent",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "exudationBloody" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Type of exudation.Bloody",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "exudationSerous" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Type of exudation.Serous",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "exudationGreenish" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Type of exudation.Greenish",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "tissueInWoundBedScarred" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Type of tissue in the wound bed.Closed/Scarred",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "tissueInWoundBedSlough" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Type of tissue in the wound bed.Slough",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "tissueInWoundBedNecrotic" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Type of tissue in the wound bed.Necrotic",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "tissueInWoundBedGranulation" => [
                            'value' => true,
                            'interpretation' => null,
                            'text' => "Type of tissue in the wound bed.Granulation",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ],
                        "tissueInWoundBedEpithelia" => [
                            'value' => false,
                            'interpretation' => null,
                            'text' => "Type of tissue in the wound bed.Epithelia",
                            'additionalData' => [
                                'presenceProbability' => [
                                    'code' => 'presenceProbability',
                                    'text' => 'Probability of the item being present in the wound as assessed by AI.',
                                    'typeOfValue' => 'percentage'
                                ]
                            ]
                        ]
                    ],
                    'scoreValue' => fn(float $value) => self::assertGreaterThanOrEqual(10, $value),
                    'interpretationCategory' => 'Stage 4',
                    'intensity' => Intensity::High,
                    'attachment' => [
                        'erythemaMaskRaw' => [
                            'title' => 'Erythema mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'erythemaMaskBinary' => [
                            'title' => 'Erythema mask binary',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'woundMaskRaw' => [
                            'title' => 'Wound mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'woundMaskBinary' => [
                            'title' => 'Wound mask binary',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'angiogenesisAndGranulatedTissueMaskRaw' => [
                            'title' => 'Angiogenesis and granulated tissue mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'angiogenesisAndGranulatedTissueMaskBinary' => [
                            'title' => 'Angiogenesis and granulated tissue mask binary',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'biofilmAndSloughMaskRaw' => [
                            'title' => 'Biofilm and slough mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'biofilmAndSloughMaskBinary' => [
                            'title' => 'Biofilm and slough mask binary',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'necrosisMaskRaw' => [
                            'title' => 'Necrosis mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'necrosisMaskBinary' => [
                            'title' => 'Necrosis mask binary',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'macerationMaskRaw' => [
                            'title' => 'Maceration mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'macerationMaskBinary' => [
                            'title' => 'Maceration mask binary',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'orthopedicMaskRaw' => [
                            'title' => 'Orthopedic mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'orthopedicMaskBinary' => [
                            'title' => 'Orthopedic mask binary',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'boneCartilageOrTendonMaskRaw' => [
                            'title' => 'Bone cartilage or tendon mask raw',
                            'height' => 768,
                            'width' => 1024
                        ],
                        'boneCartilageOrTendonMaskBinary' => [
                            'title' => 'Bone cartilage or tendon mask binary',
                            'height' => 768,
                            'width' => 1024
                        ]
                    ],
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
                    'data' => base64_encode(file_get_contents($currentDir . '/tests/resources/vitiligo.jpg')),
                ],
            ],
            'knownCondition' => [
                'conclusion' => [
                    'coding' => [[
                        'system' => 'https://icd.who.int/browse/2025-01/mms/en',
                        'systemDisplay' => 'ICD-11',
                        'version' => '2025-01',
                        'code' => 'EH90.Z',
                        'display' => 'Pressure ulcer of unspecified grade',
                    ]],
                    'text' => 'Pressure ulcer',
                ]
            ],
            'scoringSystem'  => [
                AwosiQuestionnaire::getName() => new AwosiQuestionnaire()->jsonSerialize()
            ],
        ];

        return $body;
    }
}
