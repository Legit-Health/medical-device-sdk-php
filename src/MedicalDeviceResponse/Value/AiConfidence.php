<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class AiConfidence
{
    public function __construct(
        public float $value,
        public Code $code
    ) {}
}
