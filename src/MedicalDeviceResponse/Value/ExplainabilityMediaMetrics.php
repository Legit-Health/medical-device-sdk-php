<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ExplainabilityMediaMetrics
{
    public function __construct(
        public ?float $pxToCm
    ) {
    }
}
