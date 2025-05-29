<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{KnownCondition, BodySiteCode, ScoringSystems, Subject};

readonly class SeverityAssessmentArguments implements MedicalDeviceArguments
{
    public function __construct(
        public string $image,
        public KnownCondition $knownCondition,
        public BodySiteCode $bodySiteCode,
        public ScoringSystems $scoringSystem = new ScoringSystems([])
    ) {}

    public function asArray(): array
    {
        return [
            "payload" => [
                "contentAttachment" => [
                    "data" => $this->image
                ]
            ],
            "bodySite" => $this->bodySiteCode->value,
            "knownCondition" => $this->knownCondition,
            "scoringSystem" => $this->scoringSystem->asArray(),
        ];
    }
}
