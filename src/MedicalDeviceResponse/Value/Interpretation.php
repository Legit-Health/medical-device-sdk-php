<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Interpretation
{
    public function __construct(
        public string $category,
        public Intensity $intensity
    ) {}
}
