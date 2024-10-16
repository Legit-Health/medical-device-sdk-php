<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class AdditionalData
{
    public function __construct(
        public string $code,
        public float $value
    ) {
    }
}
