<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class ModalityAdditionalData
{
    public function __construct(
        public AiConfidence $aiConfidenceClinical,
        public AiConfidence $aiConfidenceDermoscopic
    ) {}
}
