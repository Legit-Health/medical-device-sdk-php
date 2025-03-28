<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{KnownCondition, BodySiteCode, ScoringSystems, Subject};

readonly class SeverityAssessmentArguments implements MedicalDeviceArguments
{
    public function __construct(
        public string $mediaContent,
        public KnownCondition $knownCondition,
        public BodySiteCode $bodySiteCode,
        public ScoringSystems $scoringSystems = new ScoringSystems([])
    ) {}

    public function toArray(): array
    {
        return [
            "payload" => [
                "contentAttachment" => [
                    "data" => $this->mediaContent
                ]
            ],
            "bodySite" => $this->bodySiteCode->value,
            "knownCondition" => $this->knownCondition->toArray(),
            "scoringSystem" => $this->scoringSystems->toArray(),
        ];
    }
}
