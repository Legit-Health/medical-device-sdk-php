<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ScoringSystemScore
{
    public function __construct(
        public float $value,
        public ?string $interpretation
    ) {
    }
}
