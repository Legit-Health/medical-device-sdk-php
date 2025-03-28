<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceResponse\Value;

final  class Conclusion
{
    public function __construct(
        public readonly float $probability,
        public readonly ConclusionCode $code
    ) {}

    public bool $isPossible {
        get => $this->probability > 0;
    }
}
