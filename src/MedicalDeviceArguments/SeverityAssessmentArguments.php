<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments;

use LegitHealth\MedicalDevice\MedicalDeviceArguments\Params\{KnownCondition, BodySiteCode, Questionnaires, Subject};

readonly class SeverityAssessmentArguments implements MedicalDeviceArguments
{
    public function __construct(
        public string $mediaContent,
        public array $scoringSystems,
        public KnownCondition $knownCondition,
        public BodySiteCode $bodySiteCode,
        public Questionnaires $questionnaires = new Questionnaires([]),
        public ?Subject $subject = null
    ) {
    }

    public function toArray(): array
    {
        $json = [
            "media" => [
                "data" => $this->mediaContent
            ],
            "scoringSystems" => $this->scoringSystems,
            'questionnaireResponse' => $this->questionnaires->toArray(),
        ];
        $knownCondition = $this->knownCondition;
        $json['knownCondition']['conclusion'] = $knownCondition->toArray();
        $bodySiteCode = $this->bodySiteCode;
        $json['bodySite'] = $bodySiteCode->value;
        $subject = $this->subject;
        if ($subject !== null) {
            $json['subject'] = $subject->toArray();
        }
        return $json;
    }
}
