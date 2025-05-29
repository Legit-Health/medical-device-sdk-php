<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class EvolutionItemAdditionalData
{
    public function __construct(
        public ?AiConfidence $aiConfidence,
        public ?AiConfidence $presenceProbability,
        public ?ScalarValue $inflammatoryLesionCount
    ) {}
}
