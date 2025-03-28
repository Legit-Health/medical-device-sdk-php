<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ClinicalIndicator
{
    public function __construct(
        public float $hasCondition,
        public float $pigmentedLesion,
        public float $malignancy,
        public float $urgentReferral,
        public float $highPriorityReferral
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            hasCondition: $json['hasCondition'],
            pigmentedLesion: $json['pigmentedLesion'],
            malignancy: $json['malignancy'],
            urgentReferral: $json['urgentReferral'],
            highPriorityReferral: $json['highPriorityReferral'],
        );
    }
}
