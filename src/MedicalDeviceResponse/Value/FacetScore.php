<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class FacetScore
{
    /**
     * @param AdditionalData[] $additionalData
     */
    public function __construct(
        public string $code,
        public float $value,
        public array $additionalData
    ) {
    }
}
