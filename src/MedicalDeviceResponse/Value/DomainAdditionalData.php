<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class DomainAdditionalData
{
    public function __construct(
        public AiConfidence $aiConfidence
    ) {}
}
