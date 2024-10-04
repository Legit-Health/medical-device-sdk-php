<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ClinicalIndicators
{
    public function __construct(
        public float $hasCondition,
        public float $pigmentedLesion,
        public float $malignancy,
        public float $urgentReferral,
        public float $highPriorityReferral
    ) {
    }
}
