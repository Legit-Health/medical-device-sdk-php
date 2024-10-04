<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class PerformanceIndicators
{
    public function __construct(
        public float $sensitivity,
        public float $specificity,
        public float $entropy,
        public string $category,
        public array $type
    ) {
    }
}
