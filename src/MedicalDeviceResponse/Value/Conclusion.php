<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final readonly class Conclusion
{
    public function __construct(
        public float $probability,
        public ConclusionCoding $conclusionCoding
    ) {
    }

    public function isPossible(): bool
    {
        return $this->probability > 0;
    }
}
