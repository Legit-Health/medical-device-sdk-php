<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ValidityMetric
{
    public function __construct(
        public string $name,
        public bool $isValid,
        public float $score,
        public string $category
    ) {
    }
}
